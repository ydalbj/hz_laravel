<div class="content-body">
    <div>
        <h3>{{$title}}<span class="small"> 得分：{{$score}}</span> </h3> 
    </div>
    <div class="row">
        <div class="col-md-12">
            @foreach ($questions as $q)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{$q->title}}</h3>
                    </div>
                    <div class="card-body">
                        @foreach ($q->answers as $a)

                            <div class="">
                            @if ($q->type == 0 || $q->type == 1)
                                <input
                                    type="checkbox"
                                    name="a{{$loop->index}}"
                                    id="todoCheck{{$loop->index}}"
                                    @if (isset($results[intval($a->id)])) checked @endif
                                >
                                <label for="todoChec{{$loop->index}}" class=""></label>
                                {{$a->title}}
                            @else
                                {{$results[$a->id]}}
                            @endif
                            </div>
                        @endforeach
                    </div>
                    
                </div>

            @endforeach
        </div>
    </div>
</div>


<script>
Dcat.ready(function () {
    // js代码也可以放在模板里面
});
</script>