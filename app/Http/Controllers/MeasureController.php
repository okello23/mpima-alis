<?php

namespace App\Http\Controllers;

use App\Models\Measure;
use App\Models\MeasureRange;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use Illuminate\Database\QueryException;

/**
 * Contains measure resources
 * Measures are standard units and ranges of test results
 */
class MeasureController extends Controller {

    /**
     * Store a newly created resource in storage.
     *
     * @param $measures
     * @return array
     */
    public function store($measures)
    {
        $measureIds = array();
        foreach ($measures as $data) {
            $measure = new Measure;
            $measure->name = trim($data['name']);
            $measure->measure_type_id = $data['measure_type_id'];
            $measure->unit = $data['unit'];
            $measure->description = $data['description'];

            try{
                $measure->save();
                $measureIds[] = $measure->id;
            }catch(QueryException $e){
                Log::error($e);
            }

            if ($measure->isNumeric()) {
                $val['agemin'] = $data['agemin'];
                $val['agemax'] = $data['agemax'];
                $val['gender'] = $data['gender'];
                $val['rangemin'] = $data['rangemin'];
                $val['rangemax'] = $data['rangemax'];
                $val['interpretation'] = $data['interpretation'];

                // Add ranges for this measure
                for ($i=0; $i < count($val['agemin']); $i++) {
                    $measurerange = new MeasureRange;
                    $measurerange->measure_id = $measure->id;
                    $measurerange->age_min = $val['agemin'][$i];
                    $measurerange->age_max = $val['agemax'][$i];
                    $measurerange->gender = $val['gender'][$i];
                    $measurerange->range_lower = $val['rangemin'][$i];
                    $measurerange->range_upper = $val['rangemax'][$i];
                    $measurerange->interpretation = $val['interpretation'][$i];
                    $measurerange->save();
                 }
            }else if( $measure->isAlphanumeric() || $measure->isAutocomplete() ) {
                $val['val'] = $data['val'];
                $val['interpretation'] = $data['interpretation'];
                for ($i=0; $i < count($val['val']); $i++) {
                    $measurerange = new MeasureRange;
                    $measurerange->measure_id = $measure->id;
                    $measurerange->alphanumeric = $val['val'][$i];
                    $measurerange->interpretation = $val['interpretation'][$i];
                    $measurerange->save();
                }
            }
        }
        return $measureIds;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $measure
     * @return void
     */
    public function update($measure)

    {
        foreach ($measure as $id => $data) {
            $measureTypeId = $data['measure_type_id'];
            $measure = Measure::find($id);
            $measure->name = trim($data['name']);
            $measure->measure_type_id = $measureTypeId;
            $measure->unit = $data['unit'];
            $measure->description = $data['description'];
            $measure->save();
            if ($measureTypeId != Measure::FREETEXT) {
                if ($measureTypeId == Measure::NUMERIC){
                    $val['agemin'] = $data['agemin'];
                    $val['agemax'] = $data['agemax'];
                    $val['gender'] = $data['gender'];
                    $val['rangemin'] = $data['rangemin'];
                    $val['rangemax'] = $data['rangemax'];
                }else{
                    $val['val'] = $data['val'];
                }
                $val['measurerangeid'] = $data['measurerangeid'];
                $val['interpretation'] = $data['interpretation'];

                $allRangeIDs = array();

                for ($i=0; $i < count((isset($data['agemin'])) ? $val['agemin'] : $val['val']); $i++) {
                    if ($val['measurerangeid'][$i]==0) {
                        $measurerange = new MeasureRange;
                    }else{
                        $measurerange = MeasureRange::find($val['measurerangeid'][$i]);
                    }
                    $measurerange->measure_id = $measure->id;

                    if ($measureTypeId == Measure::NUMERIC){
                        $measurerange->age_min = $val['agemin'][$i];
                        $measurerange->age_max = $val['agemax'][$i];
                        $measurerange->gender = $val['gender'][$i];
                        $measurerange->range_lower = $val['rangemin'][$i];
                        $measurerange->range_upper = $val['rangemax'][$i];
                    }else{
                        $measurerange->alphanumeric = $val['val'][$i];
                    }

                    $measurerange->interpretation = $val['interpretation'][$i];

                    $measurerange->save();

                    $allRangeIDs[] = $measurerange->id;
                 }
             // Delete any pre-existing ranges for this measure_id that were not captured in the above loop.
                $allMeasureRanges = MeasureRange::where('measure_id', '=', $measure->id)->get(array('id'));
                $deleteRanges = array();

                foreach ($allMeasureRanges as $key => $value) {
                    if (!in_array($value->id, $allRangeIDs)) {
                        $deleteRanges[] = $value->id;
                    }
                }
                if(count($deleteRanges)>0)MeasureRange::destroy($deleteRanges);
            }else{
                // Since this id has no ranges, delete any references to this id in the measure_range table
                MeasureRange::where('measure_id', '=', $measure->id)->delete();
            }
        }
    }

    /**
     * Remove the specified resource from storage (soft delete).
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        //Soft delete the measure
        $measure = Measure::find($id);
        $inUseByTesttype = $measure->testTypes->toArray();

        if (empty($inUseByTesttype)) {
            // The measure is not in use
            $measure->delete();
        } else {
            // The measure is in use
            return redirect()->route('measure.index')
                ->with('message', trans('messages.failure-test-measure-in-use'));
        }
        // redirect
        return redirect()->route('measure.index')
            ->with('message', trans('messages.success-deleting-measure'));
    }
}
