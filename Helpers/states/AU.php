<?php if(isset($selectState)): ?>
    <option value="ACT" <?php echo ($selectState === 'ACT' ? 'selected' : null) ?>>Australian Capital Territory</option>
    <option value="NSW" <?php echo ($selectState === 'NSW' ? 'selected' : null) ?>>New South Wales</option>
    <option value="NT " <?php echo ($selectState === 'NT ' ? 'selected' : null) ?>>Northern Territory</option>
    <option value="QLD" <?php echo ($selectState === 'QLD' ? 'selected' : null) ?>>Queensland</option>
    <option value="SA " <?php echo ($selectState === 'SA ' ? 'selected' : null) ?>>South Australia</option>
    <option value="TAS" <?php echo ($selectState === 'TAS' ? 'selected' : null) ?>>Tasmania</option>
    <option value="VIC" <?php echo ($selectState === 'VIC' ? 'selected' : null) ?>>Victoria</option>
    <option value="WA " <?php echo ($selectState === 'WA ' ? 'selected' : null) ?>>Western Australia</option>
<?php else: ?>
    <option value="ACT">Australian Capital Territory</option>
    <option value="NSW">New South Wales</option>
    <option value="NT ">Northern Territory</option>
    <option value="QLD">Queensland</option>
    <option value="SA ">South Australia</option>
    <option value="TAS">Tasmania</option>
    <option value="VIC">Victoria</option>
    <option value="WA ">Western Australia</option>
<?php endif; ?>
