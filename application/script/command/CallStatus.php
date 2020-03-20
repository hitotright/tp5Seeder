<?php
namespace app\script\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

class CallStatus extends Command
{
    private $b_prefix = 'dxz_b';
    /**
     * 面板
     */
    protected function configure()
    {
        $this->setName('call_status')->setDescription('重置错误的通话状态');
    }

    /**
     * 入口
     * @param Input $input
     * @param Output $output
     */
    protected function execute(Input $input, Output $output)
    {
        $time = time();
        $this->output->writeln(date('Y-m-d H:i:s'));
        $this->handle();
        $time = time()-$time;
        $this->output->writeln("all steps end!".round($time/60).':'.round($time/60));
    }

    private function handle(){
        $date = date('Y-m-d H:i:s',strtotime('-6min'));
        //a端
        $phone = Db::table('call')->where('call_time','<>','0')
            ->where('call_time','<',$date)->where('call_status','in',[3,4,5])->column('caller');
        Db::table('call')->where('call_time','<>','0')
            ->where('call_time','<',$date)->where('call_status','in',[3,4])->update(['call_status'=>1]);
        Db::table('call')->where('call_time','<>','0')
            ->where('call_time','<',$date)->where('call_status','=',5)->update(['call_status'=>2]);
        $calling_phone = Db::table('call')->where('')->where('call_status','in',[3,4,5])->column('caller');
        $over_phone = array_diff($phone,$calling_phone);
        if(empty($over_phone)){
            return;
        }
        //卡状态
        Db::table('line_slot')->where('phone','in',$over_phone)->where('status','<>',2)
            ->update(['status'=>1]);
        //b端
        $b_arr = Db::table('business b')
            ->join('db_instance di','b.ins_id = di.ins_id','left')
            ->where('b.status','in',[1,2])->where('b.is_db','=',1)
            ->field('di.ins_key,b.b_id')->select();
        foreach ($b_arr as $b){
            Db::clear();
            config("{$b['ins_key']}.database",$this->b_prefix.'_'.$b['b_id']);
            Db::connect($b['ins_key'])->table('call')->where('call_time','<>','0')
                ->where('call_time','<',$date)->where('call_status','in',[3,4])->update(['call_status'=>1]);
            Db::connect($b['ins_key'])->table('call')->where('call_time','<>','0')
                ->where('call_time','<',$date)->where('call_status','=',5)->update(['call_status'=>2]);
            //卡状态
            Db::connect($b['ins_key'])->table('line_slot')->where('phone','in',$over_phone)->where('status','<>',2)
                ->update(['status'=>1]);
            //修正任务状态
            $task_id_arr=Db::connect($b['ins_key'])->table('task')->where('status','=',1)->column('task_id');
            $task_diff_arr = Db::connect($b['ins_key'])->table('call')->where('task_id','in',$task_id_arr)
                ->where('call_status','<>',1)->where('call_status','<>',2)
                ->where('call_status','<>',6)
                ->group('task_id')->column('task_id');
            $arr = array_diff($task_id_arr,$task_diff_arr);
            Db::connect($b['ins_key'])->table('task')->where('task_id','in',$arr)->update(['status'=>2]);
            Db::table('task')->where('b_id','=',$b['b_id'])->where('task_id','in',$arr)->update(['status'=>2]);
        }
    }

}