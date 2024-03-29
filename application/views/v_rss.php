<?php echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";?>
<rss version="2.0"
xmlns:dc="http://purl.org/dc/elements/1.1/"
xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
xmlns:admin="http://webns.net/mvcb/"
xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
xmlns:media="http://search.yahoo.com/mrss/"
xmlns:content="http://purl.org/rss/1.0/modules/content/">
    <channel>
        <title><?php echo $feed_name; ?></title>
        <link><?php echo $feed_url; ?></link>
        <description><?php echo $page_description; ?></description>
        <dc:language><?php echo $page_language; ?></dc:language>
        <dc:creator><?php echo $creator_email; ?></dc:creator>
        <image>
            <url><?php echo base_url('assets/img/my-logo.png');?></url>
        </image>		

        <dc:rights>Copyright <?php echo gmdate("Y", time()); ?></dc:rights>
        <admin:generatorAgent rdf:resource="http://www.aplikasiphp.com/" />

        <?php foreach($posts as $row):?>
            <item>
                <title><?php echo $row->post_title; ?></title>
                <link><?php echo site_url("detail/".$row->slug); ?></link>
                <guid><?php echo site_url("detail/".$row->slug); ?></guid>
                <pubDate><?php echo date('l, F d, Y h:i A', strtotime($row->post_date));?></pubDate>
                <description>
                    <![CDATA[
                        <?php echo $row->post_content;?>
                    ]]>
                </description>
                <enclosure url="<?php echo base_url('assets/uploads/images/'.$row->post_img);?>" length="10240" type="image/jpg" />
            </item>
        <?php endforeach; ?>
    </channel>
</rss>