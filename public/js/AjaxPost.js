class AjaxPost {
    constructor() {
        this.url = 'localhost:8000/books';
        this.bookInfos = ['a', 'b', 'c', 'd'];
        this.ajaxPost();
    }

    ajaxPost() {
        $.ajax({
            type: "POST",
            url: this.url,
            data: {
                bookInfos: this.bookInfos
            },
            success: function (data) {
                console.log(data);
            }
        });
    }
}