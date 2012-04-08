$(function() {
    var Praise = function() {
        this.data = [
            {
                cite: 'Sergey Brin',
                quote: '"lean is so awesome, wish I had known about it earlier!"'
            },
            {
                cite: 'Bill Gates',
                quote: '"I love lean\'s simplicity, very well done!"'
            },
            {
                cite: 'Ron Paul',
                quote: '"lean helps me bring peace to our planet!"'
            },
            {
                cite: 'Heidi Klum',
                quote: '"I wish I could have children with lean!"'
            },
            {
                cite: 'Chuck Norris',
                quote: '"lean is the only one who ever beat me - and I liked it!"'
            }
        ];
        this.current = 0;
    }
    /**
     * Get random entry from praise citations
     */
    Praise.prototype.getRandomCitation = function() {
        do {
            var key = Math.round((Math.random()*this.data.length));
        } while(key == this.current || this.data[key] === undefined);
        this.current = key;
        return this.data[key];
    }

    /**
     * Set content to the html elements
     * @param citation
     */
    Praise.prototype.setCitationContent = function(citation) {
        $('#praise blockquote cite').text('- ' + citation.cite);
        $('#praise blockquote p').text(citation.quote);
    }

    /**
     * Show next praise
     */
    Praise.prototype.next = function() {
        // hide the current praise
        $('#praise *').fadeOut();
        $('#praise').animate({
            width: "0"
        }, 'slow', function(){
            // element is now hidden
            // set new praise contents
            praise.setCitationContent(praise.getRandomCitation());
            // show new praise
            $('#praise *').fadeIn();
            $('#praise').animate({
                width: "423px"
            }, 'slow', function(){
                // new praise is shown, set timeout for next praise
                setTimeout(function() { praise.next(); }, 20000)
            });
        });

    }

    var praise = new Praise();
    praise.setCitationContent(praise.getRandomCitation());
    setTimeout(function() { praise.next(); }, 30000)
    window.praise = praise;

    // smooth scroll to the top
    $('#back_to_top').click(function(event){
        $('html, body').stop().animate({
            scrollTop: 0
        }, 'fast');

        event.preventDefault();
        return false;
    });
});
