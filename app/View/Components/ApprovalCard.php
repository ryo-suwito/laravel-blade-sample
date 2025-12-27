<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ApprovalCard extends Component
{
    protected array $approval;
    protected string $title;
    protected string $typeData;
    protected array $relations;
    protected string $route;
    protected array $views;
    protected string $type;
    protected array $hidden;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $title, string $typeData = 'new', array $approval)
    {
        $routes = [
            'customers' => route('approval.beneficiaries.master.show', $approval['id']),
            'companies' => route('approval.companies.master.show', $approval['id']),
            'merchants' => route('approval.merchants.master.show', $approval['id']),
            'merchant_branches' => route('approval.merchant_branches.master.show', $approval['id']),
            'partners' => route('approval.partners.master.show', $approval['id']),
            'partner_fees' => route('approval.fees.master.show', $approval['id']),
            'events' => route('approval.events.master.show', $approval['id']),
            'owners' => route('approval.owners.master.show', $approval['id']),
        ];

        $this->title = $title;
        $this->typeData = $typeData;
        $this->approval = $approval[$typeData . '_value'] ?? [];
        $this->views = $approval['views'] ?? [];
        $this->relations = $approval['properties']['relations'] ?? [];
        $this->route = $routes[$approval['table_name']];
        $this->type = $approval['type'];
        $this->hidden = $approval['hidden'] ?? [];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.approval-card', [
            'title' => $this->title,
            'typeData' => $this->typeData,
            'approval' => $this->approval,
            'views' => $this->views,
            'relations' => $this->relations,
            'viewDetailUrl' => $this->route,
            'type' => $this->type,
            'hidden' => $this->hidden,
        ]);
    }
}
