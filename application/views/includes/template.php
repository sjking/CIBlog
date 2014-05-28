<?php $this->load->helper('html'); ?>
<?php $this->load->view('includes/header'); ?>
<div id="wrap">
<div id="main">
<?php $this->load->view('includes/banner'); ?>
<?php $this->load->view($main_content); ?>
</div>
</div>
<?php $this->load->view('includes/footer'); ?>
