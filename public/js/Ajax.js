class Ajax {
    constructor(url) {
        this.url = url;
        this.url = `${this.url}`;
        this.ajaxGet(this.url, function (res) {
            const results = JSON.parse(res);
            new Display(results);
        });
    }
    ajaxGet(url, callback) {
        const req = new XMLHttpRequest();
        req.open("GET", url);
        req.addEventListener("load", function () {
            if (req.status >= 200 && req.status < 400) {
                // Appelle la fonction callback en lui passant la réponse de la requête
                callback(req.responseText);
            } else {
                //console.error(req.status + " " + req.statusText + " " + url);
                console.error(`${req.status} ${req.statusText} ${url}`);
            }
        });
        req.addEventListener("error", function () {
            console.error("Erreur réseau avec l'URL " + url);
        });
        req.send(null);
    }
}