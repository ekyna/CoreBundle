///<reference path="../../../../../../typings/index.d.ts"/>

declare module Ekyna {
    export interface Form {
        new($elem: JQuery, options?: object): Form
        getElement(): JQuery
        init($parent?: JQuery): void
        destroy(): void
        save(): void
    }

    export interface FormBuilder {
        create($elem: JQuery, options?: object): Form
    }
}

declare let Form: Ekyna.FormBuilder;

declare module 'ekyna-form' {
    export = Form;
}
