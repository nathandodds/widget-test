

    </div>
    <div class="container">
	    <img src="<?php echo DIRECTORY; ?>_admin/assets/images/storm_logo.png" alt="Storm Creative" class="storm_logo" />
	</div>
    <?php include "_admin/assets/includes/delete-popup.php" ;?>
    <script>
    	var site_path = "<?php echo DIRECTORY; ?>_admin/"; 
    	var site_route = "<?php echo str_replace('admin', '', DIRECTORY); ?>";
    </script>
    <?php if ( !!$script ): ?>
        <script data-main="<?php echo DIRECTORY; ?>_admin/assets/scripts/app/<?php echo $script; ?>" src="<?php echo DIRECTORY; ?>assets/scripts/require.min.js"></script>
    <?php endif; ?>
</body>
</html>
