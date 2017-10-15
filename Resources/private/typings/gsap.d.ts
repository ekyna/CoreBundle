///<reference path="../../../../../../typings/index.d.ts"/>

declare module 'gsap/TweenLite' {
    export = gsap.TweenLite;
}

declare module 'gsap/TweenMax' {
    export = gsap.TweenMax;
}

declare module 'gsap/TimelineLite' {
    export = gsap.TimelineLite;
}

declare module 'gsap/TimelineMax' {
    export = gsap.TimelineMax;
}

declare module 'gsap/easing/EasePack' {
    export = gsap.Ease;
}

declare var TweenLite: typeof gsap.TweenLite;
declare var TweenMax: typeof gsap.TweenMax;

declare var Power0: typeof gsap.Linear;
declare var Power1: typeof gsap.Quad;
declare var Power2: typeof gsap.Cubic;
declare var Power3: typeof gsap.Quart;
declare var Power4: typeof gsap.Quint;
declare var Back: typeof gsap.Back;
declare var Elastic: typeof gsap.Elastic;
declare var Bounce: typeof gsap.Bounce;
declare var RoughEase: typeof gsap.RoughEase;
declare var SlowMo: typeof gsap.SlowMo;
declare var SteppedEase: typeof gsap.SteppedEase;
declare var Circ: typeof gsap.Circ;
declare var Expo: typeof gsap.Expo;
declare var Sine: typeof gsap.Sine;
