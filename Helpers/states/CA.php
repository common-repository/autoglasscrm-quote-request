<?php if(isset($selectState)): ?>
    <option value="AB" <?php echo ($selectState === 'AB' ? 'selected' : null) ?>>Alberta</option>
    <option value="BC" <?php echo ($selectState === 'BC' ? 'selected' : null) ?>>British Columbia</option>
    <option value="MB" <?php echo ($selectState === 'MB' ? 'selected' : null) ?>>Manitoba</option>
    <option value="NB" <?php echo ($selectState === 'NB' ? 'selected' : null) ?>>New Brunswick</option>
    <option value="NL" <?php echo ($selectState === 'NL' ? 'selected' : null) ?>>Newfoundland and Labrador</option>
    <option value="NS" <?php echo ($selectState === 'NS' ? 'selected' : null) ?>>Nova Scotia</option>
    <option value="ON" <?php echo ($selectState === 'ON' ? 'selected' : null) ?>>Ontario</option>
    <option value="PE" <?php echo ($selectState === 'PE' ? 'selected' : null) ?>>Prince Edward Island</option>
    <option value="QC" <?php echo ($selectState === 'QC' ? 'selected' : null) ?>>Quebec</option>
    <option value="SK" <?php echo ($selectState === 'SK' ? 'selected' : null) ?>>Saskatchewan</option>
    <option value="NT" <?php echo ($selectState === 'NT' ? 'selected' : null) ?>>Northwest Territories</option>
    <option value="NU" <?php echo ($selectState === 'NU' ? 'selected' : null) ?>>Nunavut</option>
    <option value="YT" <?php echo ($selectState === 'YT' ? 'selected' : null) ?>>Yukon</option>
<?php else: ?>
    <option value="AB">Alberta</option>
    <option value="BC">British Columbia</option>
    <option value="MB">Manitoba</option>
    <option value="NB">New Brunswick</option>
    <option value="NL">Newfoundland and Labrador</option>
    <option value="NS">Nova Scotia</option>
    <option value="ON">Ontario</option>
    <option value="PE">Prince Edward Island</option>
    <option value="QC">Quebec</option>
    <option value="SK">Saskatchewan</option>
    <option value="NT">Northwest Territories</option>
    <option value="NU">Nunavut</option>
    <option value="YT">Yukon</option>
<?php endif; ?>
