
<div class="col-lg-4"> <div><?=_l('Активные звонки:');?></div> </div>
<div class="col-sm-12 " style="min-height: 200px ">
    <table  class="table table-сс table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
        <thead><tr>            
            <td>#</td>
            <td><?=_l('Кто звонит');?></td>
            <td><?=_l('Куда звонит');?></td>
            <td><?=_l('Соединен с');?></td>
            <td><?=_l('Состояние');?></td>
            <td><?=_l('Общая длит.');?></td>            
            <td><?=_l('Действия');?></td>            
        </tr></thead>
    </table>
</div>


<div class="col-lg-4"> 
    <div><?=_l('История звонков:');?></div> 
</div>
<div class="col-sm-12" style="height:50%">
    <table  class="table table-cdr stripe table-bordered dt-responsive nowrap " cellspacing="0" width="100%" >
        <thead><tr>
            <td>#</td>
            <td><?=_l('Дата');?></td>
            <td><?=_l('Тип');?></td>
            <td><?=_l('От');?></td>
            <td><?=_l('На #');?></td>
            <td><?=_l('Соединен с');?></td>            
            <th><?=_l('Состояние');?></th>                                    
            <td></td>
            <td><small><?=_l('Запись');?></small></td>
        </tr></thead>
    </table>
</div>


<script type="text/javascript">
    loadDataTables();
</script>
