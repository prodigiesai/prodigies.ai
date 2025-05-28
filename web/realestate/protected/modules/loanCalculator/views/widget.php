<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/common/css/loan-calculator.css"
      type="text/css">

<?php
$month_array = HDate::getListMonth();

$currency = param('siteCurrency', '$');
if (issetModule('currency')) {
    $currency = Currency::getCurrentCurrencyName();
}

?>

<hr>

<h2 class="h3 fint l_fint"><?= tt('Loan calculator', 'loanCalculator') ?></h2>

<form id="credit" class="col-md-6">
    <div class="form">
        <div class="credit_text form-group">
            <label><?php echo tt('Amount of credit', 'loanCalculator'); ?> (<?php echo $currency ?>)</label>
            <input type="text" name="amount" id="amount" value="<?php echo $this->amount ?>" class="form-control" />
        </div>
        <div class="credit_text form-group">
            <label><?php echo tt('Amount of credit (month)', 'loanCalculator'); ?></label>
            <input type="text" name="term" id="term" value="<?php echo $this->term ?>" class="form-control" />
        </div>
        <div class="credit_text form-group">
            <label><?php echo tt('Interest rate', 'loanCalculator'); ?> (%)</label>
            <input type="text" name="rate" id="rate" value="<?php echo $this->rate ?>" class="form-control" />
        </div>
        <div class="select form-group">
            <label><?php echo tt('Getting payments', 'loanCalculator'); ?></label>
            <div class="clearfix"></div>
            <select name="startmonth" id="startmonth" class="width100 form-control noblock">
                <?php
                $current_month = date("n");
                foreach ($month_array as $key => $value) {

                    ?>
                    <option value="<?php echo $key + 1; ?>"
                            <?php if ($current_month == $key + 1) { ?>selected="selected"<?php } ?>><?php echo $value; ?></option>
                <?php } ?>
            </select>
            <select name="startyear" id="startyear" class="width100 form-control noblock" class="form-control">
                <?php
                $current_year = date("Y");
                for ($i = $current_year - 10; $i <= $current_year + 10; $i++) {

                    ?>
                    <option value="<?php echo $i; ?>"
                            <?php if ($current_year == $i) { ?>selected="selected"<?php } ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="row buttons">
            <input class="button-blue submit-button btn btn-primary" type="submit"
                   value="<?php echo tt('Calculate', 'loanCalculator'); ?>">
        </div>
    </div>
</form>

<div class="clearfix"></div>

<p><strong><?php echo tt('Monthly payment', 'loanCalculator'); ?>:</strong> <span
            id="payment"></span> <?php echo $currency ?></p>
<p><strong><?php echo tt('Overpayment', 'loanCalculator'); ?>:</strong> <span
            id="overpay"></span> <?php echo $currency ?></p>
<div id="schedule"></div>

<script>
    $(document).ready(function () {
        $("#credit").submit(function (e) {

            e.preventDefault();

            var amount = $("#amount").val();
            var term = $("#term").val();
            var rate = $("#rate").val();
            var startmonth = $("#startmonth").val();
            var startyear = $("#startyear").val();

            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('/loanCalculator/main/ajaxCalc'); ?>",
                data: {amount: amount, term: term, rate: rate, startmonth: startmonth, startyear: startyear}
            })
                .done(function (json) {
                    var obj = JSON.parse(json);
                    $("#overpay").text(obj.overpay);
                    $("#payment").text(obj.payment);
                    $("#schedule").html(obj.schedule);
                });
        });
    });
</script>