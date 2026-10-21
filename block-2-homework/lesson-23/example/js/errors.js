// Սեփական սխալների դասեր — Դաս 23
export class ValidationError extends Error {
    constructor(field, message) {
        super(message);
        this.name = "ValidationError";
        this.field = field;
    }
}

export class ApiError extends Error {
    constructor(status, message) {
        super(message);
        this.name = "ApiError";
        this.status = status;
    }

    get isServerError() {
        return this.status >= 500;
    }
}

// Օգտագործողին հասկանալի տեքստ
export function getErrorMessage(error) {
    if (error instanceof ApiError && error.isServerError) return "Սերվերի խնդիր։ Փորձիր մի փոքր ուշ։";
    if (error instanceof ApiError) return "Տվյալները չգտնվեցին։";
    return "Չհաջողվեց կապվել։ Ստուգիր ինտերնետը։";
}
