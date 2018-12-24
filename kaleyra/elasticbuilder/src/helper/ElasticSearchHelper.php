<?php 

namespace Kaleyra\ElasticBuilder\Helper;

class ElasticSearchHelper{


    /**
     * Spliting Index Range for Summary And Raw
     */
    public static function splitIndex($startDate=null, $endDate=null, $rawPrefix=null, $summaryPrefix =null){

        if($rawPrefix || $summaryPrefix){
            $summaryIndex = '';
            $summaryIndexPrefix  = $summaryPrefix;
            $configureMonthRange = 2;
            $configureMonthRangePlusOne = $configureMonthRange+1;

            $currentDate          = date('Ymd');
            $currentDateTimestamp = strtotime($currentDate);

            $lastDate          = date("Ymd",strtotime("first day of -".$configureMonthRange. "Months"));
            $lastDateTimestamp = strtotime($lastDate);
            

            $startDateTimestamp = strtotime($startDate);
            $endDateTimestamp   = strtotime($endDate);    
                    
            $startDateParse = date_parse_from_format("Ymd", $startDate);
            $startYear  = $startDateParse['year'];
            $startMonth = $startDateParse['month'];
            $startDay   = $startDateParse['day'];

            if($startMonth < 10){ $startMonth = '0'.$startMonth; }
            if($startDay < 10)  { $startDay   = '0'.$startDay; }

            $endDateParse = date_parse_from_format("Ymd", $endDate);
            $endYear  = $endDateParse['year'];
            $endMonth = $endDateParse['month'];
            $endDay   = $endDateParse['day'];

            if($endMonth < 10){ $endMonth = '0'.$endMonth; }
            if($endDay   < 10){ $endDay   = '0'.$endDay; }


            if( $startDateTimestamp >= $lastDateTimestamp && $endDateTimestamp <= $currentDateTimestamp ){        
                    $indexDates['summaryIndex']     = '';
                    $indexDates['startSummaryDate'] = '';
                    $indexDates['endSummaryDate']   = '';
                    $indexDates['startEsDate']      = $startYear.$startMonth.$startDay;
                    $indexDates['endEsDate']        = $endYear.$endMonth.$endDay;
            }else{        
                    if( $startDateTimestamp <= $lastDateTimestamp && ($endDateTimestamp <= $currentDateTimestamp && $endDateTimestamp >= $lastDateTimestamp) ){                    
                        $summaryStartDate        = $startYear.$startMonth."01";                    
                        $summaryEndDate          = date ('Ymd', strtotime("last day of -".$configureMonthRangePlusOne. "month"));                    
                        $summaryYearMonthPattern = date("Ym", strtotime($summaryStartDate));                    

                        while( strtotime($summaryStartDate) <= strtotime($summaryEndDate) ) {		                
                            $summaryIndex.= $summaryIndexPrefix.$summaryYearMonthPattern.',';
                            $summaryYearMonthPattern = date ("Ym", strtotime("+1 Month", strtotime($summaryStartDate)));
                            $summaryStartDate        = date ("Ymd", strtotime("+1 Month", strtotime($summaryStartDate)));
                        }

                        $indexDates['summaryIndex']     = $summaryIndex;
                        $indexDates['startSummaryDate'] = $startYear.'-'.$startMonth.'-'.$startDay;
                        $indexDates['endSummaryDate']   = date('Y-m-d', strtotime("last day of -".$configureMonthRangePlusOne. "month"));
                        $indexDates['startEsDate']      = date('Ymd', strtotime("first day of -".$configureMonthRange. "month"));
                        $indexDates['endEsDate']        = $endYear.$endMonth.$endDay;              

                    }else if( $startDateTimestamp <= $lastDateTimestamp && $endDateTimestamp <= $lastDateTimestamp ){                    

                        $summaryStartDate        = $startYear.$startMonth."01";                    
                        $summaryEndDate          = $endYear.$endMonth."01";
                        $summaryYearMonthPattern = date("Ym", strtotime($summaryStartDate));                    

                        while( strtotime($summaryStartDate) <= strtotime($summaryEndDate) ) {		                
                            $summaryIndex.= $summaryIndexPrefix.$summaryYearMonthPattern.',';
                            $summaryYearMonthPattern = date ("Ym" , strtotime("+1 Month", strtotime($summaryStartDate)));
                            $summaryStartDate        = date ("Ymd", strtotime("+1 Month", strtotime($summaryStartDate)));
                        }

                        $indexDates['summaryIndex']     = $summaryIndex;
                        $indexDates['startSummaryDate'] = $startYear.'-'.$startMonth.'-'.$startDay;
                        $indexDates['endSummaryDate']   = $endYear.'-'.$endMonth.'-'.$endDay;
                        $indexDates['startEsDate']      = '';
                        $indexDates['endEsDate']        = '';
                    }
            }
                                 
            if(is_array($indexDates)){                
                $this->setSummaryIndex($indexDates['summaryIndex']);
                $this->setRawIndex($indexDates['startEsDate'], $indexDates['endEsDate'], $rawPrefix);
            }
        }else{
            return false;
        }       
    }
    
    /**
     * Setter for Raw Index
     * @param
     * @return
     */
    public static function generateIndexRange( $rawPrefix = null, $indexRange1 = null, $indexRange2= null)
    {                
        if($rawPrefix){

            $dateIndex   = '';
            $indexPrefix = $rawPrefix;        

            if($indexRange1 != '' && $indexRange2 != ''){
                while( strtotime($indexRange1) <= strtotime($indexRange2) ) {
                    $dateIndex.= $indexPrefix.$indexRange1.',';
                    $indexRange1 = date ("Ymd", strtotime("+1 day", strtotime($indexRange1)));
                }
            }else if($indexRange1 != '' && $indexRange2 == ''){
                $dateIndex = $indexPrefix.$indexRange1;
            }else{
                return false;
            }
            
            return $dateIndex;
        }else{
            return false;
        }
    }
}