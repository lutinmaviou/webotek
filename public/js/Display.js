class Display {
    constructor(books) {
        this.displayInfos(books);
        this.displayButton(books);
    }

    displayInfos(books) {
        for (let i = 0; i < books.items.length; i++) {
            let title = '';
            let author = '';
            let img = '';
            let description = '';
            title = $('<h3 class="mt-5 pt-5 mb-2">' + books.items[i].volumeInfo.title + '</h3>');
            if (books.items[i].volumeInfo.hasOwnProperty('authors') === true) {
                author = $('<h5 class="mt-3">' + books.items[i].volumeInfo.authors + '</h4>');
            } else {
                author = $('<h5 class="mt-3" style ="color: black">Auteur(s) inconnu(s)</h4>');
            }
            img = $('<img class="mt-5 mb-5"></img>');
            if (books.items[i].volumeInfo.hasOwnProperty('imageLinks') === true) {
                img.attr('src', books.items[i].volumeInfo.imageLinks.thumbnail);
                img.attr('alt', 'Image du livre : ' + books.items[i].volumeInfo.title);
            } else {
                img.attr('src', 'img/téléchargement.jfif');
            }
            if (books.items[i].volumeInfo.hasOwnProperty('description') === true) {
                description = $('<p class="mt-4 mb-5 px-5 text-justify">' + books.items[i].volumeInfo.description + '</p>');
            } else {
                description = $('<p class="mt-4 mb-5 px-5" style ="color: black">Pas encore de description</p>');
            }
            const separation = $('<hr>');
            const buttons = $('<div class="mb-5"><button class="btn btn-light md-4">Ajouter</button><button class="btn btn-light ml-4">Réserver</button></div>')
            $('#display-results').append(title, author, img, description, buttons, separation);
        }
    }

    displayButton(books) {
        const button = $('<button type="button" id="show-more" class="btn btn-lg btn-block">Voir plus de résultats</button>');
        $('#display-button').append(button);
    }
}