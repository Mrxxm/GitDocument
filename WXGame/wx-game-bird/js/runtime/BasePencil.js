// 铅笔基类
import {Sprite} from "../base/Sprite.js";
import {Director} from "../Director.js";
import {DataStore} from "../base/DataStore.js";

export class BasePencil extends Sprite{
    constructor(image) {
        super(image,
            0,0,
            image.width, image.height,
            DataStore.getInstance().canvas.width,0,
            image.width, image.height);
        // 铅笔垂直变化坐标
        this.top = Director.getInstance().top;
        // 铅笔水平变化坐标
        this.pencilX = this.x;
        // 铅笔的移动速度
        this.pencilSpeed = Director.getInstance().moveSpeed;
    }

    draw() {
        this.pencilX = this.pencilX - this.pencilSpeed;
        super.draw(this.img,
            this.srcX,
            this.srxY,
            this.srcW,
            this.srcH,
            this.pencilX,
            this.y,
            this.width,
            this.height);
    }
}