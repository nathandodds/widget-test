	    </div>
	    <?php include "assets/includes/footer.php"; ?>
	</div>
    <script>
    	var site_path = "<?php echo DIRECTORY; ?>"; 
    	var current_page = "<?php echo (!!$current_header_page ? $current_header_page : ""); ?>";
    </script>
    <?php if ( !!$script ): ?>
        <script data-main="<?php echo DIRECTORY; ?>assets/scripts/<?php echo ENV; ?>/<?php echo $script; ?>" src="<?php echo DIRECTORY; ?>assets/scripts/require.min.js"></script>
    <?php endif; ?>
</body>
</html>
