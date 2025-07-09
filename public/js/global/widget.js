'use strict';

import {RequestHandler} from './request.js';

export async function get_system_unit_count() {
    return new Promise((resolve, reject) => {
        const count = new countUp.CountUp("count-system-unit");
        (new RequestHandler).get("/widget/system-unit-count")
            .then((res) => {
                count.update(res.payload);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
            });
    });
}

export async function get_laptop_count() {
    return new Promise((resolve, reject) => {
        const count = new countUp.CountUp("count-laptop");
        (new RequestHandler).get("/widget/laptop-count")
            .then((res) => {
                count.update(res.payload);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
            });
    });
}

export async function get_printer_count() {
    return new Promise((resolve, reject) => {
        const count = new countUp.CountUp("count-printer");
        (new RequestHandler).get("/widget/printer-count")
            .then((res) => {
                count.update(res.payload);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
            });
    });
}

export async function get_cellphone_count() {
    return new Promise((resolve, reject) => {
        const count = new countUp.CountUp("count-cellphone");
        (new RequestHandler).get("/widget/cellphone-count")
            .then((res) => {
                count.update(res.payload);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
            });
    });
}

export async function get_monitor_count() {
    return new Promise((resolve, reject) => {
        const count = new countUp.CountUp("count-monitor");
        (new RequestHandler).get("/widget/monitor-count")
            .then((res) => {
                count.update(res.payload);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
            });
    });
}

export async function get_router_count() {
    return new Promise((resolve, reject) => {
        const count = new countUp.CountUp("count-router");
        (new RequestHandler).get("/widget/router-count")
            .then((res) => {
                count.update(res.payload);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
            });
    });
}
