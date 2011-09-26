var CurrentQuestion = Spine.Controller.create({
    proxied: ["render"],

    init: function() {
        this.num = 0;
        this.question = Question.init(this.questionData[0]);
        Question.bind("change", this.render);
    },
    render: function() {
        
    }
});