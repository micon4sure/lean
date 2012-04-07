<div id="container">
    <a href="http://github.com/klawd-prime/lean"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://a248.e.akamai.net/assets.github.com/img/7afbc8b248c68eb468279e8c17986ad46549fb71/687474703a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f6461726b626c75655f3132313632312e706e67" alt="Fork me on GitHub"></a>
    <div class="clearfix">
        <h1><span id="lean">lean</span> - the <span id="microlib">µlib</span></h1>
        <ul id="topnav">
            <li><a href="/whatis">whatis</a></li>
            <li><a href="/code">code</a></li>
            <li id="download"><a href="/download">download</a></li>
        </ul>
    </div>
    <div id="teaser">
        <div class="clearfix">
            <h2>lean gives you freedom</h2>
            <div id="praise">
                <blockquote>
                    <cite>klawd-prime</cite>
                    <p id="quote">It's so awesome, wish I had known about it earlier!</p>
                </blockquote>
            </div>
        </div>
    </div>
    <span id="teaser_bottom"></span>
    <? $this->view->display(); ?>
    <footer>
        <a href="http://github.com/klawd-prime/lean">lean µlib</a>, <a href="http://github.com/klawd-prime">Michael Saller</a>, <a href="http://time.is">2012</a>
    </footer>
</div>
<script type="text/javascript">
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
            $('#praise blockquote cite').text(citation.cite);
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
    });
</script>