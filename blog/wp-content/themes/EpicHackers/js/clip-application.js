(function($) {
  $(function() {
        
    // TABLES COLLAPSE
    $('.collapse').on('shown.bs.collapse', function(){
    $(this).parent().find(".icon-database").removeClass("icon-database").addClass("icon-wrong");
    }).on('hidden.bs.collapse', function(){
    $(this).parent().find(".icon-wrong").removeClass("icon-wrong").addClass("icon-database");
    });

    $('.collapse').on('shown.bs.collapse', function(){
    $(this).parent().find(".icon-up").removeClass("icon-up").addClass("icon-down");
    }).on('hidden.bs.collapse', function(){
    $(this).parent().find(".icon-down").removeClass("icon-down").addClass("icon-up");
    });
    // Table scroll
    $('.scrollable').slimScroll({
      height: '120px'
    });

      // POPOVER & TOOLTIP
    $("[rel='popover']").popover();
    $("[rel='tooltip']").tooltip();

    // PAGINATION
    $('#pagination_sm').clipPagination({
        totalPages: 10,
        visiblePages: 6,
        onPageClick: function (event, page) {
            $('#page-content_sm').text('Page ' + page);
        }
    })

    $('#pagination_nm').clipPagination({
        totalPages: 8,
        visiblePages: 5,
        onPageClick: function (event, page) {
            $('#page-content_nm').text('Page ' + page);
        }
    });

    $('#pagination_lg').clipPagination({
        totalPages: 6,
        visiblePages: 4,
        onPageClick: function (event, page) {
            $('#page-content_lg').text('Page ' + page);
        }
    })
    // PROGRESS-BAR
    $(window).ready(function(e){
        $.each($('div.progress-bar'),function(){
          $(this).css('width', $(this).attr('aria-valuetransitiongoal')+'%');
        });
    });

    // BOOTSTRAP SWITCH
    $("[name='my-checkbox']").bootstrapSwitch();

    $(function () {
      $('.switch')['bootstrapSwitch']();
    });


    $('input[name="my-checkbox"]').bootstrapSwitch('state', true, true);

    $.fn.bootstrapSwitch.defaults.size = 'large';
    $.fn.bootstrapSwitch.defaults.onColor = 'success';

    // RADIO SWITCH
    $('.radio1').on('switch-change', function () {
        $('.radio1').bootstrapSwitch('toggleRadioState');
    });
    $('.radio1').on('switch-change', function () {
        $('.radio1').bootstrapSwitch('toggleRadioStateAllowUncheck');
    });
    $('.radio1').on('switch-change', function () {
        $('.radio1').bootstrapSwitch('toggleRadioStateAllowUncheck', false);
    });

    // KNOB
    $(function() {
      $(".dial").knob();
    });
    // SORTABLE
    $(function() {
      $('.sortable').sortable();
      $('.handles').sortable({
        handle: 'span'
      });
      $('.connected').sortable({
        connectWith: '.connected'
      });
      $('.exclude').sortable({
        items: ':not(.disabled)'
      });
    });
    // make code pretty
    window.prettyPrint && prettyPrint();

  });
  
})(jQuery);