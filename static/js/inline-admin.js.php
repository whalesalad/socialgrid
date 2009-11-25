<script type="text/javascript">
    SG_SERVICES = {
        <?php 
        foreach ($default_services as $service_name => $service):
            echo $service_name.': true,'."\n";
        endforeach;
        ?>
    }
</script>