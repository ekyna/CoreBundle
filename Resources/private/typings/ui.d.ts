interface LoadingSpinner {
    (): JQuery;
    (action: string): JQuery;
}

interface JQuery {
    loadingSpinner: LoadingSpinner;
}

declare let jQLS:JQuery;

declare module 'ekyna-ui' {
    export = jQLS;
}
