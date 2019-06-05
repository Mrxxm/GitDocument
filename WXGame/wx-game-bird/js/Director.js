// 导演类
import {DataStore} from "./base/DataStore.js";
import {UpPencil} from "./runtime/UpPencil.js";
import {DownPencil} from "./runtime/DownPencil.js";

export class Director {

    static getInstance() {
        if (!Director.instance) {
            Director.instance = new Director();
        }
        return Director.instance;
    }

    constructor() {
        this.dataStore  = DataStore.getInstance();
        this.moveSpeed  = 2;
        this.isGameOver = true;
    }

    static random(lower, upper) {
        return Math.floor(Math.random() * (upper - lower + 1)) + lower;
    }

    createPencil() {
        // land 112
        // 420
        // 114
        this.top = Director.random((DataStore.getInstance().canvas.height - 420 - (DataStore.getInstance().canvas.height / 5)), 420 - 112);
        this.dataStore.get('pencilUp').push(new UpPencil());
        this.dataStore.get('pencilDown').push(new DownPencil());
    }

    // 小鸟点击事件
    birdsEvent() {
        for (let i = 0; i <= 2; i++) {
            // 将当前小鸟的birdsY坐标赋值给y,在draw中刷新时，会重新将y加上向上偏移赋值给birdsY
            this.dataStore.get('birds').y[i] =
                this.dataStore.get('birds').birdsY[i];
        }
        this.dataStore.get('birds').time = 0;
    }

    // 判断小鸟撞击地板和铅笔
    check() {
        const birds = this.dataStore.get('birds');
        const land = this.dataStore.get('land');
        const pencilUpArr = this.dataStore.get('pencilUp');
        const pencilDownArr = this.dataStore.get('pencilDown');
        const score = this.dataStore.get('score');

        if (birds.birdsY[0] + birds.birdsHeight[0] >= land.y) {
            console.log('小鸟撞击地板了!!!');
            this.isGameOver = true;
        }

        // 小鸟的边框模型
        const birdBorder = {
            top: birds.birdsY[0],
            bottom:birds.birdsY[0] + birds.birdsHeight[0],
            left:birds.birdsX[0],
            right:birds.birdsX[0] + birds.birdsWidth[0]
        };

        // 上铅笔建模
        const length = pencilUpArr.length;
        for (let i = 0; i < length; i++) {
            const pencilUp = pencilUpArr[i];
            const pencilUpBorder = {
                top: pencilUp.y,
                bottom:pencilUp.y + pencilUp.height,
                left:pencilUp.pencilX,
                right:pencilUp.pencilX + pencilUp.width
            };

            if (Director.isUpStrike(birdBorder, pencilUpBorder)) {
                console.log("撞到上铅笔了!!!");
                this.isGameOver = true;
            }
        }

        // 下铅笔建模
        for (let i = 0; i < length; i++) {
            const pencilDown = pencilDownArr[i];
            const pencilDownBorder = {
                top: pencilDown.y,
                bottom:pencilDown.y + pencilDown.height,
                left:pencilDown.pencilX,
                right:pencilDown.pencilX + pencilDown.width
            };

            if (Director.isDownStrike(birdBorder, pencilDownBorder)) {
                console.log("撞到下铅笔了!!!");
                this.isGameOver = true;
            }
        }

        // 加分逻辑
        if (birds.birdsX[0] > pencilUpArr[0].pencilX + pencilUpArr[0].width && score.isScore) {
            // 关闭加分
            score.isScore = false;
            score.scoreNumber++;
            // 添加震动
            wx.vibrateShort({
                success: function () {
                    console.log('震动成功');
                }
            });
        }
    }

    // 小鸟撞击上铅笔
    static isUpStrike(bird, pencil) {
        let s = false;

        // 可以继续飞行的情况
        if (bird.top > pencil.bottom ||
            bird.right < pencil.left ||
            bird.left > pencil.right
        ) {
            s = true;
        }

        return !s;
    }

    // 小鸟撞击下铅笔
    static isDownStrike(bird, pencil) {
        let s = false;

        // 可以继续飞行的情况
        if (bird.bottom < pencil.top ||
            bird.right < pencil.left ||
            bird.left > pencil.right
        ) {
            s = true;
        }

        return !s;
    }

    run() {
        this.check();
        if (!this.isGameOver) {
            // 背景
            this.dataStore.get("background").draw();
            // 上下铅笔
            const pencilUpArr = this.dataStore.get('pencilUp');
            const pencilDownArr = this.dataStore.get('pencilDown');
            // 铅笔销毁
            if (pencilUpArr[0].pencilX + pencilUpArr[0].width <= 0 && pencilUpArr.length === 2) {
                pencilUpArr.shift();
                pencilDownArr.shift();
                // 开启加分
                this.dataStore.get('score').isScore = true;
            }
            // 铅笔创建
            if (pencilUpArr[0].pencilX <= (DataStore.getInstance().canvas.width - pencilUpArr[0].width) / 2 && pencilUpArr.length === 1) {
                this.createPencil();
            }
            pencilUpArr.forEach(function (pencilUp) {
                pencilUp.draw();
            });
            pencilDownArr.forEach(function (pencilDown) {
                pencilDown.draw();
            });
            // 陆地
            this.dataStore.get('land').draw();
            // 小鸟
            this.dataStore.get('birds').draw();
            // 分数
            this.dataStore.get('score').draw();
            let timer = requestAnimationFrame(() => this.run());
            this.dataStore.put('timer', timer);
        } else {
            // 关闭音乐
            this.dataStore.bgm.stop();
            console.log("音频暂停");
            // 按钮
            this.dataStore.get('startButton').draw();
            console.log("Game over!!!");
            cancelAnimationFrame(this.dataStore.get('timer'));
            this.dataStore.destory();
            // 垃圾回收
            wx.triggerGC();

        }
    }
}