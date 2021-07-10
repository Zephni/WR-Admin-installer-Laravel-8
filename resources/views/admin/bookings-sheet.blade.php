@extends('admin.layouts.admin')

@section('content')

<div class="row mb-3">
    <div class="col-12">
        <h2>Bookings sheet - {{ DateTime::createFromFormat('Y-m-d', $year.'-'.$month.'-01')->format('F Y') }}</h2>
        <hr>
    </div>
</div>
<div class="row mb-3">
    <div class="col-12">
        <div class="d-none d-md-flex justify-content-between align-items-center">
            <div>
                <form class="form form-inline bookings-sheet-form" action="{{ route('admin-bookings-sheet') }}" method="get">
                    <select class="form-control font-weight-bold" name="month">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ ($i < 10) ? '0'.$i : $i }}" @if($i == (int)$month) selected="selected" @endif> {{ date('F', mktime(0,0,0,$i, 1, $year)) }}</option>
                        @endfor
                    </select>
                    <select class="form-control font-weight-bold mx-2" name="year">
                        @for($i = date('Y')-2; $i <= date('Y')+2; $i++)
                            <option value="{{ $i }}" @if($i == (int)$year) selected="selected" @endif>{{ $i }}</option>
                        @endfor
                    </select>
                    <input type="submit" value="Submit" class="btn btn-primary">
                </form>
            </div>
            <div class="pl-3 d-flex align-items-center font-weight-bold">
                Key: &nbsp;
                <span class='d-inline bg-success mx-2' style="width: 16px; height: 16px;"></span> 2 remaining <span class='mr-3'></span>
                <span class='d-inline bg-warning mx-2' style="width: 16px; height: 16px;"></span> 1 remaining <span class='mr-3'></span>
                <span class='d-inline bg-danger mx-2' style="width: 16px; height: 16px;"></span>  0 remaining
            </div>
        </div>

        <div class="d-block d-md-none">
            <form class="form bookings-sheet-form" action="{{ route('admin-bookings-sheet') }}" method="get">
                <select class="form-control font-weight-bold mb-2" name="month">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ ($i < 10) ? '0'.$i : $i }}" @if($i == (int)$month) selected="selected" @endif> {{ date('F', mktime(0,0,0,$i, 1, $year)) }}</option>
                    @endfor
                </select>
                <select class="form-control font-weight-bold mb-2" name="year">
                    @for($i = date('Y')-2; $i <= date('Y')+2; $i++)
                        <option value="{{ $i }}" @if($i == (int)$year) selected="selected" @endif>{{ $i }}</option>
                    @endfor
                </select>
                <input type="submit" value="Submit" class="form-control btn btn-primary mb-2">
            </form>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-12">

        <table class="table table-sm table-striped" style="font-size: 16px;">
            <tr class='thead bg-dark text-white'>
                <td class="font-weight-bold" style="width: 8%;">Day</td>
                @foreach($ruleColumns as $ruleColumn)
                    <td class="font-weight-bold" style="width: 23%;">{{ ucfirst($ruleColumn) }}</td>
                @endforeach
            </tr>

            @for($i = 1; $i <= $daysInMonth; $i++)
                <tr>
                    <td>{{ $i.date('S', mktime(0, 0, 0, (int)$month, $i)) }}</td>
                    @foreach($ruleColumns as $ruleColumn)
                        <?php
                            $ads = \App\Http\Controllers\BookingsSheet::getAds($year, $month, (($i < 10) ? '0'.$i : $i), $ruleColumn);
                        ?>

                        <td>
                            @if(count($ads) > 0)
                                <?php $adGroups = []; ?>
                                @foreach($ads as $ad)
                                    <?php
                                        if($ad->solus == 1)
                                            $adGroups[$ad->spot] = 3;
                                        elseif(!array_key_exists($ad->spot, $adGroups))
                                            $adGroups[$ad->spot] = 1;
                                        else
                                            $adGroups[$ad->spot]++;
                                    ?>
                                @endforeach
                                @foreach($adGroups as $groupKey => $groupInt)
                                    <?php
                                        $spanClass = '';
                                        if($groupInt == 1) $spanClass = 'bg-success text-white';
                                        elseif($groupInt == 2) $spanClass = 'bg-warning text-dark';
                                        elseif($groupInt == 3) $spanClass = 'bg-danger text-white';
                                        elseif($groupInt > 3) $spanClass = 'bg-dark text-white';
                                    ?>
                                    <span class="{{ $spanClass }} px-md-1 d-block d-md-inline text-center font-weight-bold">{{ $groupKey }}</span>
                                @endforeach
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endfor
            
        </table>

    </div>
</div>

@endsection