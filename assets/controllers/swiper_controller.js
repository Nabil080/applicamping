import { Controller } from '@hotwired/stimulus';
import Swiper from 'swiper';


export default class extends Controller {
    connect() {    
        var dragSize = this.element.dataset.dragSize ? this.element.dataset.dragSize : 50;
        var freeMode = this.element.dataset.freeMode ? this.element.dataset.freeMode : false;
        var loop = this.element.dataset.loop ? this.element.dataset.loop : false;
        var slidesDesktop = this.element.dataset.slidesDesktop ? this.element.dataset.slidesDesktop : 4;
        var slidesTablet = this.element.dataset.slidesTablet ? this.element.dataset.slidesTablet : 3;
        var slidesMobile = this.element.dataset.slidesMobile ? this.element.dataset.slidesMobile : 2.5;
        var spaceBetween = this.element.dataset.spaceBetween ? this.element.dataset.spaceBetween : 20;
    
        var swiper = new Swiper(this.element, {
            direction: 'horizontal',
            loop: loop,
            freeMode: freeMode,
            spaceBetween: spaceBetween,
            breakpoints: {
                1920: {
                    slidesPerView: slidesDesktop
                },
                992: {
                    slidesPerView: slidesTablet
                },
                320: {
                    slidesPerView: slidesMobile
                }
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
            },
            scrollbar: {
                el: '.swiper-scrollbar',
                draggable: true,
                dragSize: dragSize
            }
        });
    
    
        
    }


}
