declare module Ekyna {
    export interface Flags {
        load(): void
    }
}

declare let Flags:Ekyna.Flags;

declare module 'ekyna-flags' {
    export = Flags;
}
