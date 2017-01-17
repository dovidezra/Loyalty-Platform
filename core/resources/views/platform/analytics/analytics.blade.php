<div class="container">
<div class="row m-t">
  <div class="col-sm-6">
<?php
if (! isset($coupons)) {
?>
    <div class="card-box">
      <h1>{{ trans('global.no_data_found') }} </h1>
    </div>

<?php
} else { 
?>
    <div class="card-box" style="padding:13px">
      <select id="coupons" class="select2-required">
<?php
echo '<option value="">' . trans('global.all_coupons') . '</option>';

foreach($coupons as $key => $row) {
  $selected = ($row['id'] == $coupon_id) ? ' selected' : '';
  echo '<option value="' . $key . '"' . $selected . '>' . $row['name'] . '</option>';
}
?>
      </select>
<script>
$('#coupons').on('change', function() {
  document.location = ($(this).val() == '') ? '#/analytics/<?php echo $date_start ?>/<?php echo $date_end ?>' : '#/analytics/<?php echo $date_start ?>/<?php echo $date_end ?>/' + $(this).val();
});
</script>
    </div>
  </div>
  <div class="col-sm-6 text-center m-b-20">
      <div class="form-control" id="reportrange" style="cursor:pointer;padding:20px; width:100%; display:table"> <i class="fa fa-calendar" style="margin:0 10px 0 0"></i> <span></span> </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12">

      <div class="card-box">
        <h3 class="page-title">{{ trans('global.views') }}</h3>
        <div id="combine-chart">
          <div id="combine-chart-container" class="flot-chart" style="height: 320px;"> </div>
        </div>
      </div>

  </div>
</div>

<div class="row">
  <div class="col-lg-6">

    <div class="card-box">
      <h3 class="page-title">{{ trans('global.os') }}</h3>
      <div id="os-donut-chart">
        <div class="flot-chart" style="height: 180px;">
        </div>
      </div>
    </div>

  </div>
  <div class="col-lg-6">

    <div class="card-box">
      <h3 class="page-title">{{ trans('global.browsers') }}</h3>
      <div id="browser-donut-chart">
        <div class="flot-chart" style="height: 180px;">
        </div>
      </div>
     </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
      <div class="card-box">
        <h3 class="page-title">{{ trans('global.map') }}</h3>
        <div id="world-map-markers" style="height: 400px"></div>
       </div>

  </div>
  <div class="col-md-6">
      <div class="card-box">
        <h3 class="page-title">{{ trans('global.members') }}</h3>
        <table class="table" id="members-table">
          <thead>
            <tr>
              <th>&nbsp;</th>
              <th class="text-center">{{ trans('global.views') }}</th>
              <th class="text-center">{{ trans('global.redemptions') }}</th>
            </tr>          
          </thead>
          <tbody>
<?php foreach($coupon_members as $coupon_member) { ?>
            <tr>
              <td>{{ $coupon_member['name'] . ' &lt;' . $coupon_member['email'] . '&gt;' }}</td>
              <td class="text-center">{{ $coupon_member['views'] }}</td>
              <td class="text-center">{{ $coupon_member['redemptions'] }}</td>
            </tr> 
<?php } ?>
          </tbody>
        </table>
<script>
$('#members-table').DataTable({
  "order": [[ 1, "desc" ], [ 2, "desc" ]]
});
</script>
      </div>

  </div>
</div>
<script src="{{ asset('assets/js/jvectormap.min.js') }}"></script>
<script src="{{ asset('assets/js/maps/jquery-jvectormap-world-mill.js') }}"></script>

<script>
$('#reportrange span').html(moment('<?php echo $date_start ?>').format('MMMM D, YYYY') + ' - ' + moment('<?php echo $date_end ?>').format('MMMM D, YYYY'));

$('#reportrange').daterangepicker({
  format: 'MM-DD-YYYY',
  startDate: moment('<?php echo $date_start ?>').format('MM-D-YYYY'),
  endDate: moment('<?php echo $date_end ?>').format('MM-D-YYYY'),
  minDate: moment('<?php echo $first_date ?>').format('MM-D-YYYY'),
  maxDate: '<?php echo date('m/d/Y') ?>',
  dateLimit: {
      days: 60
  },
  showDropdowns: true,
  showWeekNumbers: true,
  timePicker: false,
  timePickerIncrement: 1,
  timePicker12Hour: true,
  ranges: {
   '<?php echo trans('global.today') ?>': [ moment(), moment() ],
   '<?php echo trans('global.yesterday') ?>': [ moment().subtract(1, 'days'), moment().subtract(1, 'days') ],
   '<?php echo trans('global.last_7_days') ?>': [ moment().subtract(6, 'days'), moment() ],
   '<?php echo trans('global.last_30_days') ?>': [ moment().subtract(29, 'days'), moment() ],
   '<?php echo trans('global.this_month') ?>': [ moment().startOf('month'), moment().endOf('month') ],
   '<?php echo trans('global.last_month') ?>': [ moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month') ]
  },

  opens: 'left',
  drops: 'down',
  buttonClasses: ['btn', 'btn-sm'],
  applyClass: 'btn-primary',
  cancelClass: 'btn-inverse',
  separator: ' {{ strtolower(trans('global.to')) }} ',
  locale: {
    applyLabel: '<?php echo trans('global.submit') ?>',
    cancelLabel: '<?php echo trans('global.reset') ?>',
    fromLabel: '<?php echo trans('global.date_from') ?>',
    toLabel: '<?php echo trans('global.date_to') ?>',
    customRangeLabel: '<?php echo trans('global.custom_range') ?>',
    daysOfWeek: ['<?php echo trans('global.su') ?>', '<?php echo trans('global.mo') ?>', '<?php echo trans('global.tu') ?>', '<?php echo trans('global.we') ?>', '<?php echo trans('global.th') ?>', '<?php echo trans('global.fr') ?>','<?php echo trans('global.sa') ?>'],
      monthNames: ['<?php echo trans('global.january') ?>', '<?php echo trans('global.february') ?>', '<?php echo trans('global.march') ?>', '<?php echo trans('global.april') ?>', '<?php echo trans('global.may') ?>', '<?php echo trans('global.june') ?>', '<?php echo trans('global.july') ?>', '<?php echo trans('global.august') ?>', '<?php echo trans('global.september') ?>', '<?php echo trans('global.october') ?>', '<?php echo trans('global.november') ?>', '<?php echo trans('global.december') ?>'],
      firstDay: 1
  }
});

$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
  $('#reportrange span').html(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
  var start = picker.startDate.format('YYYY-MM-DD');
  var end = picker.endDate.format('YYYY-MM-DD');

  var sl = '{{ $sl }}';
  document.location = (sl == '') ? '#/analytics/' + start + '/' + end : '#/analytics/' + start + '/' + end + '/' + sl;
});

//Combine graph data
var statViews = [
<?php foreach($coupon_range as $date => $row) { ?>
[(new Date(<?php echo $row['y'] ?>, <?php echo $row['m'] - 1 ?>, <?php echo $row['d'] + 1 ?>)).getTime(), <?php echo $row['views'] ?>],
<?php } ?>
];

var statRedemptions = [
<?php foreach($coupon_range as $date => $row) { ?>
[(new Date(<?php echo $row['y'] ?>, <?php echo $row['m'] - 1 ?>, <?php echo $row['d']  + 1?>)).getTime(), <?php echo $row['redemptions'] ?>],
<?php } ?>
];
var ticks = [
<?php foreach($coupon_range as $date => $row) { ?>
[(new Date(<?php echo $row['y'] ?>, <?php echo $row['m'] - 1 ?>, <?php echo $row['d'] + 1 ?>)).getTime(), '<?php echo $row['m'] . '/' . $row['d'] ?>'],
<?php } ?>
];
var combinelabels = ["Views", "Redemptions"];
var combinedatas = [statViews, statRedemptions];

// first correct the timestamps - they are recorded as the daily
// midnights in UTC+0100, but Flot always displays dates in UTC
// so we have to add one hour to hit the midnights in the plot
for (var i = 0; i < statViews.length; ++i) {
  statViews[i][0] += 60 * 60 * 1000;
}

for (var i = 0; i < statRedemptions.length; ++i) {
  statRedemptions[i][0] += 60 * 60 * 1000;
}

function weekendAreas(axes) {

  var markings = [],
    d = new Date(axes.xaxis.min);

  // go to the first Saturday

  d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
  d.setUTCSeconds(0);
  d.setUTCMinutes(0);
  d.setUTCHours(0);

  var i = d.getTime();

  // when we don't set yaxis, the rectangle automatically
  // extends to infinity upwards and downwards

  do {
    markings.push({ xaxis: { from: i, to: i + 2 * 24 * 60 * 60 * 1000 }, color:"#2b323a" });
    i += 7 * 24 * 60 * 60 * 1000;
  } while (i < axes.xaxis.max);

  return markings;
}

var options = {
  series : {
    shadowSize : 0
  },
  grid : {
    markings: weekendAreas,
    hoverable : true,
    clickable : true,
    tickColor : "#f9f9f9",
    borderWidth : 1,
    borderColor : "hsla(0,0%,93%,.1)"
  },
  colors : ["#fff", "#3bafda"],
  tooltip : true,
  tooltipOpts : {
    content : "%y %s",
    defaultTheme : false
  },
  legend : {
    position : "ne",
    margin : [0, -24],
    noColumns : 0,
    labelBoxBorderColor : null,
    labelFormatter : function(label, series) {
      // just add some space to labes
      return '' + label + '&nbsp;&nbsp;';
    },
    width : 30,
    height : 2
  },
  yaxis : {
    tickColor : 'hsla(0,0%,93%,.1)',
    tickDecimals: 0,
    font : {
      color : '#bdbdbd'
    }
  },
  xaxis : {
    mode: "time", 
    timeformat: "%Y-%m-%d",
    ticks: ticks,
    tickLength: 5,
    tickColor : '#f5f5f5',
    font : {
      color : '#bdbdbd'
    }
  }
};

var data = [{
  label : combinelabels[0],
  data : combinedatas[0],
  lines : {
    show : true,
    fill : false
  },
  points : {
    show : true
  }
}, {
  label : combinelabels[1],
  data : combinedatas[1],
  lines : {
    show : false
  },
  bars : {
    show : true,
    align: "center",
    fill: true,
    barWidth: (1000*60*60*12)
  }
}];

$.plot($("#combine-chart #combine-chart-container"), data, options);


var osData = [
<?php foreach($coupon_os as $coupon_operating_system) { ?>
{
  label : "{{ $coupon_operating_system['os'] }}",
  data : {{ $coupon_operating_system['hits'] }}
},
<?php } ?>
];

var osOptions = {
  series : {
    pie : {
      show : true,
      innerRadius : 0.5
    }
  },
  legend : {
    show : true,
    labelFormatter : function(label, series) {
      return '<div style="font-size:14px;">&nbsp;' + label + '</div>'
    },
    labelBoxBorderColor : null,
    margin : 10,
    width : 20,
    padding : 1
  },
  grid : {
    hoverable : true,
    clickable : true
  },
  colors : ["#3bafda", "#26c6da", "#80deea", "#00b19d"],
  tooltip : true,
  tooltipOpts : {
    content : "%s, %p.0%"
  }
};

$.plot($("#os-donut-chart .flot-chart"), osData, osOptions);


var browserData = [
<?php foreach($coupon_browsers as $coupon_browser) { ?>
{
  label : "{{ $coupon_browser['client'] }}",
  data : {{ $coupon_browser['hits'] }}
},
<?php } ?>
];

var browserOptions = {
  series : {
    pie : {
      show : true,
      innerRadius : 0.5
    }
  },
  legend : {
    show : true,
    labelFormatter : function(label, series) {
      return '<div style="font-size:14px;">&nbsp;' + label + '</div>'
    },
    labelBoxBorderColor : null,
    margin : 10,
    width : 20,
    padding : 1
  },
  grid : {
    hoverable : true,
    clickable : true
  },
  colors : ["#3bafda", "#26c6da", "#80deea", "#00b19d"],
  tooltip : true,
  tooltipOpts : {
    content : "%s, %p.0%"
  }
};

$.plot($("#browser-donut-chart .flot-chart"), browserData, browserOptions);


$(window).resize(function(event) {
  if ($("#combine-chart #combine-chart-container").length) {
    $.plot($("#combine-chart #combine-chart-container"), data, options);
  }
  if ($("#os-donut-chart .flot-chart").length) {
   $.plot($("#os-donut-chart .flot-chart"), osData, osOptions);
  }
  if ($("#browser-donut-chart .flot-chart").length) {
   $.plot($("#browser-donut-chart .flot-chart"), osData, osOptions);
  }
});

var markers = [
<?php foreach($coupon_latlng as $i => $row) { ?>
  {
    latLng : [<?php echo $row['lat'] ?>, <?php echo $row['lng'] ?>],
    name : '<?php echo str_replace("'", "\'", $row['city']) ?>: <?php echo $row['views'] ?>'
  },
<?php } ?>
];

  $('#world-map-markers').vectorMap({
    map : 'world_mill',
    normalizeFunction : 'polynomial',
    hoverOpacity : 0.7,
    hoverColor : false,
    regionStyle : {
    initial : {
      fill : '#3bafda'
    }
  },
  markerStyle: {
    initial: {
      r: 9,
      'fill': '#a288d5',
      'fill-opacity': 0.9,
      'stroke': '#fff',
      'stroke-width' : 7,
      'stroke-opacity': 0.4
    },
    hover: {
      'stroke': '#fff',
      'fill-opacity': 1,
      'stroke-width': 1.5
    }
  },
  backgroundColor : 'transparent',
  markers : markers
  });

</script>
<?php } ?>