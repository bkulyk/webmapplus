<div id="gmaps_search_form">
  <h2>Find the location closest to you: </h2>
  <form action="<?php echo JRoute::_('index.php?option=com_webmapplus&view=webmapplus');?>" method="POST">
    
    <label for='searchAddress'>Address:</label> 
    <input type="text" size="25" class="searchAddress" name="searchAddress" id='searchAddress' value=""/>
    <?php if($this->params->get( 'map_search_range',  1) == 1): ?>
      <label for='range'>Range:</label> 
      <select name="range" id='range'>
        <option value="5">5 <?php echo $this->units;?></option>
        <option value="10">10 <?php echo $this->units;?></option>
        <option value="20" selected>20 <?php echo $this->units;?></option>
        <option value="50">50 <?php echo $this->units;?></option>
        <option value="100">100 <?php echo $this->units;?></option>
        <option value="200">200 <?php echo $this->units;?></option>
        <option value="400">400 <?php echo $this->units;?></option>
        <option value="800">800 <?php echo $this->units;?></option>
       </select>
	  <?php endif; ?>
	   
    <?php if($this->params->get( 'map_search_range',  1) == 0): ?>
      <input type="hidden" name="range" value="0"/>
    <?php endif; ?>	 

    <?php if($this->params->get( 'map_category_filter',  0) == 1): ?>
      <div id="gmaps_category_form">
        <h2>Category Filter: </h2>
        <label>Category:</label>
        <?php echo $this->category; ?>
      </div>
    <?php endif; ?>

    <div style="clear: both"></div>
    <input name="submit" type="submit" value="Search" />    
  </form>
</div>