// 小鸟类
import {Sprite} from "../base/Sprite.js";
import {DataStore} from "../base/DataStore.js";

export class Birds extends Sprite{
    constructor() {
        const image = Sprite.getImage('birds');
        super(
            image,
            0,0,
            image.width, image.height,
            0,0,
            image.width, image.height
        );
        // 小鸟的三种状态需要一个数组去存储
        // 小鸟的宽是34，上下边框是10， 小鸟的左右边距是9
        this.clippingX = [
            9,
            9 + 34 + 18,
            9 + 34 + 18 + 34 + 18
        ];
        this.clippingY      = [10, 10, 10];
        this.clippingWidth  = [34, 34, 34];
        this.clippingHeight = [24, 24, 24];
        // 小鸟水平初始坐标
        const birdX = DataStore.getInstance().canvas.width / 4;
        this.birdsX = [birdX, birdX, birdX];
        // 小鸟垂直初始坐标
        const birdY = DataStore.getInstance().canvas.height / 2;
        this.birdsY = [birdY, birdY, birdY];
        // 小鸟宽度
        const birdWidth = 34;
        this.birdsWidth = [birdWidth, birdWidth, birdWidth];
        // 小鸟高度
        const birdHeight = 24;
        this.birdsHeight = [birdHeight, birdHeight, birdHeight];

        // 小鸟垂直变化坐标
        this.y = [birdY, birdY, birdY];

        this.index = 0;
        this.count = 0;
        this.time = 0;
    }

    draw() {
        // 切换三只小鸟的速度
        const speed = 1;
        // const speed = 0.2;
        this.count = this.count + speed;
        // 0,1,2
        if (this.index >= 2) {
            this.count = 0;
        }
        this.index = this.count;
        // 减速器的作用
        // this.index = Math.floor(this.count);

        // 小鸟下落
        const g = 0.98 / 2.5; // 模拟重力加速度
        const offsetUp = 30; // 当time=0时，直接减去offsetUp就起到向上偏移的效果
        const offsetY = (g * this.time * (this.time - offsetUp)) / 2; // 自由落体运行
        for (let i = 0; i <= 2; i++) {
            this.birdsY[i] = this.y[i] + offsetY;
        }
        this.time++;

        super.draw(this.img,
            this.clippingX[this.index],
            this.clippingY[this.index],
            this.clippingWidth[this.index],
            this.clippingHeight[this.index],
            this.birdsX[this.index],
            this.birdsY[this.index],
            this.birdsWidth[this.index],
            this.birdsHeight[this.index]
            );
    }
}