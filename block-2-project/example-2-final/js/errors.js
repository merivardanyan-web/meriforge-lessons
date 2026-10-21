// Սեփական սխալների դասեր — JS Դաս 23
// instanceof-ով catch-ում տարբերում ենք՝ ինչ է պատահել, և ցույց տալիս ճիշտ հաղորդագրություն

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

export class NotFoundError extends Error {
    constructor(message) {
        super(message);
        this.name = "NotFoundError";
    }
}

export class ValidationError extends Error {
    constructor(field, message) {
        super(message);
        this.name = "ValidationError";
        this.field = field;
    }
}

// Օգտագործողին հասկանալի տեքստ ցանկացած սխալի համար
export function getErrorMessage(error) {
    if (error instanceof NotFoundError) return error.message;
    if (error instanceof ApiError && error.isServerError) return "Սերվերի խնդիր։ Փորձիր մի փոքր ուշ։";
    if (error instanceof ApiError) return "Տվյալները չգտնվեցին։";
    return "Չհաջողվեց բեռնել։ Ստուգիր ինտերնետը։";
}
