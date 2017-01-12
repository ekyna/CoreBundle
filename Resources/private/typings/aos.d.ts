interface AosInterface {
    init():void
    refresh():void
    refreshHard():void
}

declare let AOS:AosInterface;

declare module "aos" {
    export = AOS;
}
