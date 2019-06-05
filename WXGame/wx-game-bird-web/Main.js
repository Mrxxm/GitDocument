// 游戏开始入口类
import {ResourceLoader} from "./js/base/ResourceLoader.js";
import {StartButton} from "./js/player/StartButton.js";
import {Background} from "./js/runtime/Background.js";
import {DataStore} from "./js/base/DataStore.js";
import {Score} from "./js/player/Score.js";
import {Birds} from "./js/player/Birds.js";
import {Land} from "./js/runtime/Land.js";
import {Director} from "./js/Director.js";

export class Main {
    constructor() {
        this.canvas    = document.getElementById("game_canvas");
        this.ctx       = this.canvas.getContext('2d');
        this.dataStore = DataStore.getInstance();
        this.director  = Director.getInstance();
        const loader   = ResourceLoader.create();
        loader.onLoaded(map => this.onResourceFirstLoader(map));
        
    }

    onResourceFirstLoader(map) {
        this.dataStore.ctx = this.ctx; // 长期保存
        this.dataStore.res = map;      // 长期保存
        this.init();
    }

    init() {
        // 全局控制
        this.director.isGameOver = false;
        // 将dataStore成员属性按名字取出，重新存入可控成员属性map中
        this.dataStore.put('background', Background)
                      .put('land', Land)
                      .put('pencilUp', [])
                      .put('pencilDown', [])
                      .put('birds', Birds)
                      .put('startButton', StartButton)
                      .put('score', Score);
        this.registerEvent();
        // 要创建多组铅笔put方法放置Director中
        this.director.createPencil();
        this.director.run();
    }

    // 点击事件
    registerEvent() {
        // 再一次使用箭头函数
        this.canvas.addEventListener('touchstart', e => {
            // 屏蔽JS的事件冒泡
            e.preventDefault();
            if (this.director.isGameOver) {
                console.log("游戏开始");
                this.init();
            } else {
                this.director.birdsEvent();
            }
        })
    }
}