class App {
    constructor(nbResults) {
        this.api = 'https://www.googleapis.com/books/v1/volumes?q=';
        this.search;
        this.param = 'subject:';
        this.subText;
        this.startIndex = 0;
        this.nbResults = nbResults;
        this.index;
        this.options = `&maxResults=${this.nbResults}&langRestrict=fr`;
        this.url;
        this.bookSearch = this.bookSearch.bind(this);
        this.addBook = this.addBook.bind(this);
        this.searchByCategory();
        this.searchCatBySelect();
        this.seeMore = this.seeMore.bind(this);
        this.assign();
    }

    // Find books by typing a text in the search field

    bookSearch() {
        this.subText = '';
        $('#display-results').empty();
        $('#display-button').empty();
        this.search = $('#search').val();
        this.url = this.api + this.search + this.options;
        new AjaxGet(this.url);
    }

    // Find books with the select element of categories

    searchCatBySelect() {
        const that = this;
        $("select").change(() => {
            $("select option:selected").each(function () {
                that.subText = this.value;
                $('#display-results').empty();
                $('#display-button').empty();
                that.url = that.api + that.param + that.subText + that.options;
                new AjaxGet(that.url);
            });
        });
    }

    // Find books with the categories buttons

    searchByCategory() {
        const btn = $('#categories button');
        for (let i = 0; i < btn.length; i++) {
            $(btn[i]).click(() => {
                $('#display-results').empty();
                $('#display-button').empty();
                this.subText = btn[i].value;
                this.url = this.api + this.param + this.subText + this.options;
                new AjaxGet(this.url);
            });
        }
    }

    // Show more results

    seeMore() {
        $('#display-button').empty();
        if (this.subText) {
            this.startIndex += this.nbResults;
            this.index = `&startIndex=${this.startIndex}`;
            this.url = this.api + this.param + this.subText + this.index + this.options;
            new AjaxGet(this.url);
        } else {
            this.startIndex += this.nbResults;
            this.index = `&startIndex=${this.startIndex}`;
            this.url = this.api + this.search + this.index + this.options;
            new AjaxGet(this.url);
        }
    }

    // "Click" functions assignation
    assign() {
        $('#go').click(this.bookSearch);
        $('#display-button').click(this.seeMore);
        $('#toto').click(this.addBook);
    }
}

new App(3);