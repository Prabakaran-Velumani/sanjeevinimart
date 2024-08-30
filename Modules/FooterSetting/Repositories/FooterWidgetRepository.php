<?php
namespace Modules\FooterSetting\Repositories;

use \Modules\FooterSetting\Entities\FooterWidget;
use \Modules\FrontendCMS\Entities\DynamicPage;
use Illuminate\Support\Facades\Log;
class FooterWidgetRepository {

    protected $widget;
    protected $dynamicPage;

    public function __construct(FooterWidget $widget, DynamicPage $dynamicPage)
    {
        $this->widget = $widget;
        $this->dynamicPage = $dynamicPage;
    }

    public function getAll(){
        return $this->dynamicPage::where('is_static',1)->get();
    }
    public function getAllCompany(){
        return $this->widget::where('section','1')->orderBy('id','ASC')->get();
    }
    public function getAllAccount(){
        return $this->widget::where('section','2')->orderBy('id','ASC')->get();
    }
    public function getAllService(){
        return $this->widget::where('section','3')->orderBy('id','ASC')->get();
    }
    public function getAllOurLegal(){
        return $this->widget::where('section','4')->orderBy('id','ASC')->get();
    }


    public function save($data){
        Log::info('*****data'.json_encode($data));
        $page = DynamicPage::findOrFail($data['page']);
        $data['slug'] = $page->slug;
        $data['status'] = 1;
        $data['is_static'] = 0;
        $data['category'] = $data['section_id'];
        $data['section'] = $data['section_id'];
        $data['user_id'] = auth()->user()->id;
        $this->widget->fill($data)->save();
    }

    public function update($data, $id)
    {
        $page = DynamicPage::findOrFail($data['page']);
        $widget_update =  $this->widget::where('id',$id)->first();
        $data['slug'] = $page->slug;
        $widget_update->fill($data)->save();
    }

    public function edit($id){
        $widget = $this->widget->findOrFail($id);
        return $widget;
    }

    public function statusUpdate($data, $id){
        return $this->widget::where('id',$id)->first()->update([
            'status' => $data['status']
        ]);
    }

    public function delete($id){
        return $this->widget->findOrFail($id)->delete();
    }
}
