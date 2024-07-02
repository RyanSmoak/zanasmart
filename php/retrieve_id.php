<?php
$response=[
    'status'=>0,
    'message'=>'',
    'exam_id'=>null
];
session_start();
@$exam_id=$_SESSION['exam_id'];
if(isset($exam_id))
{
    $response['message']="Data retreived successfully";
    $response['status']=1;
    $response['exam_id']=$exam_id;
    echo json_encode($response);
}
else{
    $response['message']="Data not found";
    $response['status']=0;
    echo json_encode($response);
}
?>
