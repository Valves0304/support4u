
    $('.nav-button, .clear-overlay').on('click', function (e) {
        e.preventDefault();
        $('.nav-button a i').toggleClass("visible");
        $('nav').toggleClass('open');
    });

    if($('.user').text().length == 0){
        $('.user').hide();
    }else{
        $('.user').show();
    }

    $('.card .open-modal').click(function (event) {
        event.preventDefault();
        this.blur();
        var modalHeader = $(this).closest('.card').find('.card-header h3').text();
        var modalSubHeader = $(this).closest('.card').find('.card-content div:not(.open-modal)').clone();
        var modalContent = $(this).closest('.card').find('.card-list').html();
        $('#defaultModal section.modal-title').html("");
        $('#defaultModal section.modal-content').html("");
        $('#defaultModal section.modal-title').append(modalHeader);
        $('#defaultModal section.modal-content').append(modalSubHeader).append('<div class=list></div>');
        $('#defaultModal section.modal-content div.list').append(modalContent);
        $('#defaultModal').modal();
    });

    $('a.icon-action.add').off('click').on('click', function () {
        var originalRow = $(this).closest('article .pure-g');
        var selectedUnit = originalRow.find('select').val();
        var cloneRow = originalRow.clone();

        cloneRow.find("select option[value=" + selectedUnit + "]").attr("selected", true);
        cloneRow.find('.icon-action.add').removeClass('add').addClass('remove');
        cloneRow.find('.fa-plus-circle').removeClass('fa-plus-circle').addClass('fa-minus-circle');
        $('.list-items').append(cloneRow);

        originalRow.find('input').val('');
        originalRow.find('select option[value=0]').attr("selected", true);

        appendIconActionFunction();
    });

    function appendIconActionFunction() {
        $('a.icon-action.remove').off('click').on('click', function () {
            $(this).closest('.pure-g').remove();
        });
    }

    appendIconActionFunction();
