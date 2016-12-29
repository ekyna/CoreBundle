///<reference path="../../../../../../../typings/globals/jquery/index.d.ts"/>

interface JQuery {
    loadingSpinner(): JQuery;
    loadingSpinner(action: string): JQuery;
}

declare let jQLS:JQuery;

declare module 'ekyna-ui' {
    export = jQLS;
}
