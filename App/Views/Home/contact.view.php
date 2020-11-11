<?php /** @var Array $data */ ?>
<div class="container">
    <h1 class="mt-5">Kde nás najdete?</h1>
    <p class="lead">Wiki, World Wide Web (WWW) site that can be modified or contributed to by users. Wikis can be dated to 1995, when American computer programmer Ward Cunningham created a new collaborative technology for organizing information on Web sites. Using a Hawaiian term meaning “quick,” he called this new software WikiWikiWeb, attracted by its alliteration and also by its matching abbreviation (WWW).</p>

    <p>
        Wikis were inspired in part by Apple’s HyperCard program, which allowed users to create virtual “card stacks” of information with a host of connections, or links, among the various cards. HyperCard in turn drew upon an idea suggested by Vannevar Bush in his 1945 Atlantic Monthly article “As We May Think.” There Bush envisioned the memex, a machine that would allow readers to annotate and create links between articles and books recorded on microfilm. HyperCard’s “stacks” implemented a version of Bush’s vision, but the program relied upon the user to create both the text and the links. For example, one might take a musical score of a symphony and annotate different sections with different cards linked together.
    </p>
</div>

<div class="container">

<?php
$lat = $data['lat'];//"33.80766";
$log =  $data['lng']; //"-33.76714";

?>
<iframe id="map-canvas" class="map_part" width="100%" height="600" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=100%&amp;height=600&amp;hl=en&amp;q=<?php echo $lat.",".$log; ?>&amp;t=&amp;z=3&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
</div>