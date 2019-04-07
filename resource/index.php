<?php
//מידע בתגי מטא של הדף
@$system['site_title'].=" - המילים שאחרי שם האתר שיופיעו בכותרת";
@$system['site_keywords']="מילות מפתח של הדף";
@$system['site_description']="תיאור הדף";

if(@page()){
// <<< from here output <<<
?>
כאן יבוא תוכן הדף
<?php
// >>> end of output >>>
}
?>