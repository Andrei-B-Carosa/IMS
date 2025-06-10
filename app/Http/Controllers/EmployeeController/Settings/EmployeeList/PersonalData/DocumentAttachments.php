<?php

namespace App\Http\Controllers\EmployeeController\Settings\EmployeeList\PersonalData;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\HrisEmployeeDocument;
use App\Service\Reusable\Datatable;
use App\Service\Select\DocumentTypeOptions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentAttachments extends Controller
{

    protected $isRegisterEmployee = false;

    public function dt(Request $rq)
    {
        $emp_id = isset($rq->emp_id) && $rq->emp_id != "undefined" ? Crypt::decrypt($rq->emp_id):null;

        $data = HrisEmployeeDocument::where([['is_deleted',null],['emp_id',$emp_id]])->orderBy('id', 'ASC') ->get();
        $data->transform(function ($item, $key) {

            $last_updated_by = null;
            $last_updated_at = null;
            if($item->updated_by != null){
                $last_updated_by = $item->updated_by_emp->fullname();
                $last_updated_at = Carbon::parse($item->updated_at)->format('m-d-y h:iA');
            }elseif($item->created_by !=null){
                $last_updated_by = $item->created_by_emp->fullname();
                $last_updated_at = Carbon::parse($item->created_at)->format('m-d-y h:iA');
            }

            $item->count = $key + 1;
            $item->last_updated_by = $last_updated_by;
            $item->last_updated_at = $last_updated_at;
            $item->file_type = $item->file_id?config('document_values.document_type.'.$item->file_id) :$item->others;
            $item->encrypted_id = Crypt::encrypt($item->id);
            return $item;

        });

        $table = new Datatable($rq, $data);
        $table->renderTable();

        return response()->json([
            'draw' => $table->getDraw(),
            'recordsTotal' => $table->getRecordsTotal(),
            'recordsFiltered' =>  $table->getRecordsFiltered(),
            'data' => $table->getRows()
        ]);
    }

    public function view($rq,$components,$employee)
    {
        $isRegisterEmployee = $this->isRegisterEmployee;

        $request = $rq->merge(['id' => null, 'type'=>'options']);
        $document_type = (new DocumentTypeOptions)->list($rq);

        $options = [
            'document_type'=>$document_type,
        ];
        return view($components.'document-attachments', compact('employee','isRegisterEmployee','options'))->render();
    }

    public function update(Request $rq)
    {
        try {
            DB::beginTransaction();

            if (!$rq->hasFile('files')) {
                return response()->json(['status' => 'error', 'message' => 'No file uploaded', 'payload' => '']);
            }

            $user_id = Auth::user()->emp_id;
            $emp_id = Crypt::decrypt($rq->emp_id);
            $filePath = null;

            $emp_no = Employee::where('id', $emp_id)->value('emp_no');
            $parsed_emp_no = preg_replace('/[^A-Za-z0-9]/', '',$emp_no);

            $others = $rq->others && strtolower($rq->file_type)=='others' ? $rq->others: null;
            $file_type_id = strtolower($rq->file_type)!='others' && is_null($others) ? $rq->file_type: null;

            if(is_null($others) || is_null($file_type_id)){
                return response()->json(['status' => 'error', 'message' => 'Something went wrong, try again later', 'payload' => '']);
            }

            $document = $file_type_id ? config('document_values.document_type.'.$file_type_id) : ($others ? $others:null);
            $column = $file_type_id?['file_id' => $file_type_id] : ($others ? ['others' => strtolower($others)]:null);

            if (is_null($document) || is_null($column)) {
                return response()->json(['status' => 'error', 'message' => 'Something went wrong, try again later', 'payload' => '']);
            }

            $parsed_document = preg_replace('/\s+/', '' ,$document);
            $directory = "employee/{$parsed_emp_no}/documents";
            $timestamp = now()->format('YmdHis');

            // example : EMP00010_TranscriptofRecord_pdf
            $filename = "{$parsed_document}_{$parsed_emp_no}_{$timestamp}.{$rq->file('files')->getClientOriginalExtension()}";
            $filePath = $rq->file('files')->storeAs($directory, $filename, 'public');

            if ($filePath && Storage::disk('public')->exists($filePath) && is_array($column)) {
                $column['emp_id'] = $emp_id;
                $column['filename'] = $filename;
                $column['created_by'] = $user_id;
                HrisEmployeeDocument::create($column);
            }
            DB::commit();
            return [ 'status' => 'success','message'=>'Update is success', 'payload' => ''];
        } catch (\Exception $e) {
            DB::rollback();
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            return [ 'status' => 'error', 'message' => $e->getMessage(), 'payload'=>'' ];
        }

    }

    public function delete(Request $rq)
    {
        try {
            DB::beginTransaction();

            $id = Crypt::decrypt($rq->id);
            $query = HrisEmployeeDocument::find($id);

            $emp_no = preg_replace('/[^A-Za-z0-9]/', '',$query->employee->emp_no);
            $filePath = 'employee/'.$emp_no.'/documents/'.$query->filename;
            if (Storage::disk('public')->exists($filePath)) {
                if (Storage::disk('public')->delete($filePath)) {
                    $query->delete();
                    DB::commit();
                    return [
                        'status' => 'success',
                        'message'=>'Document is removed',
                        'payload' => HrisEmployeeDocument::where([['is_deleted',null],['emp_id',$query->emp_id]])->count()
                    ];
                }
            }

            DB::rollback();
            return [ 'status' => 'error','message'=>'Something went wrong, try again later', 'payload' => ''];
        } catch (\Exception $e) {
            DB::rollback();
            return [ 'status' => 'error', 'message' => $e->getMessage() ];
        }
    }

    public function download_document(Request $rq)
    {
        try {
        $id = Crypt::decrypt($rq->id);
        $query = HrisEmployeeDocument::find($id);

        $emp_no = preg_replace('/[^A-Za-z0-9]/', '',$query->employee->emp_no);
        $filePath = 'employee/'.$emp_no.'/documents/'.$query->filename;

        // Check if file exists in storage
        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['status' => 'error', 'message' => 'File not found']);
        }

        $downloadUrl = asset('storage/'.$filePath);
        return ['status' => 'success', 'payload' => $downloadUrl];
        } catch (\Exception $e) {
            DB::rollback();
            return [ 'status' => 'error', 'message' => $e->getMessage() ];
        }
    }

    public function view_document(Request $rq)
    {
        try {
        $id = Crypt::decrypt($rq->id);
        $query = HrisEmployeeDocument::find($id);

        $emp_no = preg_replace('/[^A-Za-z0-9]/', '',$query->employee->emp_no);
        $filePath = 'employee/'.$emp_no.'/documents/'.$query->filename;

        // Check if file exists in storage
        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['status' => 'error', 'message' => 'File not found']);
        }

        $downloadUrl = asset('storage/'.$filePath);
        return ['status' => 'success', 'payload' => $downloadUrl];
        } catch (\Exception $e) {
            DB::rollback();
            return [ 'status' => 'error', 'message' => $e->getMessage() ];
        }
    }

}
