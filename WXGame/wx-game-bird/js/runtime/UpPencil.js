// 上铅笔类
import {BasePencil} from "./BasePencil.js";
import {Sprite} from "../base/Sprite.js";

export class UpPencil extends BasePencil{
    constructor() {
        const image = Sprite.getImage('pencilUp');
        super(image);
    }

    draw() {
        this.y = this.top - this.height;
        super.draw();
    }
}