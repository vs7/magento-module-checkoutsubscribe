document.observe("dom:loaded", function() {
    Review.prototype.save  = Review.prototype.save.wrap(function(parentMethod) {
        if ($$('input:checked[type=radio][name=newsletter-subscribe]')[0] === undefined) {
            alert('Please choose subscribe option');
            return;
        }
        var subscribe = $$('input:checked[type=radio][name=newsletter-subscribe]')[0].value;
        if (this.agreementsForm) {
            if ($$('input[type=hidden][name=is_subscribed]')[0] === undefined) {
                this.agreementsForm.insert('<input type="hidden" name="is_subscribed" value="' + subscribe + '" />');
            } else {
                $$('input[type=hidden][name=is_subscribed]')[0].value = subscribe;
            }
        }
        parentMethod();
    });
});