<h2>What is lean</h2>
<h3>TL;DR: Some cold hard facts</h3>
<ul>
    <li>lean is a micro lib written in PHP5.3.</li>
    <li>lean has everything you need to build a full fledged website, not more.</li>
    <li>lean has an application class, utilizing <a href="http://slimframework.com">Slim's</a> Routing and dispatching.</li>
    <li>It's lightweight, it's fun, it's lean!</li>
</ul>
<h3>In depth</h3>
<p>
lean is a small and lightweight library in PHP 5.3 and should be included in every project! It gives you easy and small components for templating, I18N, etc. along with some powerful utility classes such as <em>lean\Dump</em> 
</p>
<p>
    On top of that, it brings /:controller/:action functionality to the  <a href="http://slimframework.com">Slim</a> micro framework, complete with layout and partials!
</p>
<p>
    What we mean by "lean gives you freedom" is that it does not force you to do stuff a certain way and it does not push a monolithic, overpowered, overengineered and ultimately deadweight library on you.
    It is clear in what the components do and it does them on a barebone level. If you need something additional, go write it! We feel that this is a better approach than the huge pseudo-Java frameworks out there and if you must needs use them, at least have lean by your side to shed some light.<br/>
    lean is the hero PHP needs, but not the one it deserves right now.
</p>
<p>
    So! Enough with the chitchat, here's a feature complete feature list!
</p>

<table>
    <tr>
        <td id="features">
            <h4>Features</h4>
            <ul class="features">
                <li>/:controller/:action routing</li>
                <li>Small but powerful template system</li>
                <li>Views with [ document > layout > view ] structure</li>
                <li>Partials</li>
                <li>Environments to stage your development</li>
                <li>Form and form element abstraction</li>
                <li>I18N (translations only at this point)</li>
                <li>Session abstraction</li>
                <li>Text manipulation</li>
                <li>Powerful data dump class</li>
                <li>Migration management</li>
            </ul>
            <h4>Planned features</h4>
            <ul class="features">
                <li>Full fledged I18N</li>
                <li>Scaffolding</li>
            </ul>
        </td>
        <td id="woman">
            <img src="/images/angry-woman.jpg" alt="angry woman"/>

            <p>
                This woman had to deal with monolithic frameworks!<br/>
                She should've used lean instead and saved herself a good deal of trouble!
            </p>

        </td>
    </tr>
</table>