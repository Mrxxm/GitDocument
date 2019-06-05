class Animal {

    constructor(name = '', age = 0) {
        this.name = name;
        this.age = age;
    }

    say() {
        console.log(this.name, this.age);
    }
}

class Cat extends Animal {
    constructor(name, age) {
        super(name, age);
    }
}