<?php

namespace App\Http\Controllers;

use App\Entity\District;
use App\Entity\FeasibilityTestTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Entity\Project;
use App\Laravue\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;

use function PHPSTORM_META\type;

class DashboardController extends Controller
{
    //

    public function index(Request $request) {
      
    }   

    public function proposalCount(Request $request){
        // pemrakarsa
        $q = DB::table('projects')
        ->select('projects.required_doc', DB::raw('count(*) as total'));
        if ($request->initiatorId){
            return response($q->where('projects.id_applicant', $request->initiatorId)
            ->groupBy('projects.required_doc')->get());
        }

        if($request->formulatorId){
            $proposals = $q
            ->leftJoin('formulator_teams', 'projects.id', '=', 'formulator_teams.id_project')
            ->leftJoin('formulator_team_members', 'formulator_teams.id', '=', 'formulator_team_members.id_formulator_team')
            ->leftJoin('formulators', 'formulators.id', '=', 'formulator_team_members.id_formulator')
            ->where('formulators.id', $request->formulatorId)->groupBy('projects.required_doc')->get();
            return response($proposals);
        }

        return response($q->groupBy('projects.required_doc')->get());
    }

    public function latestActivities(Request $request){
        if($request->initiatorId) {
            return response(Project::with(['address'])
              ->where('id_applicant', $request->initiatorId)
              ->orderBy('created_at', 'desc')->paginate(3));
        }
        if($request->formulatorId){
            return response(Project::with(['address'])
            ->select('projects.*', 'project_address.*')
            ->join('formulator_teams', 'projects.id', '=', 'formulator_teams.id_project')
            ->leftJoin('formulator_team_members', 'formulator_teams.id', '=', 'formulator_team_members.id_formulator_team')
            ->leftjoin('formulators', 'formulators.id', '=', 'formulator_team_members.id_formulator')
            ->leftJoin('project_address', 'project_address.id_project', 'projects.id')
            ->where('formulators.id', $request->formulatorId)
            ->orderBy('projects.created_at', 'desc')->paginate(3));
        }

        return response(Project::with(['address'])
        ->select('projects.*', 'initiators.name as applicant')
        ->leftJoin('initiators', 'initiators.id', 'projects.id_applicant')
        ->orderBy('projects.created_at', 'desc')->paginate(5));
    }

    public function status(Request $request)
    {
        $total = 0;
        $accepted = 0;
        $on_progress = 0;
        $rejected = 0;

        $type = Auth::user()->roles->first()->name == 'admin' ? '' : 'tuk';
        $role = Auth::user()->roles->first()->name;

        $total = Project::where(function($q) use($request) {
                    if($request->period && $request->start && $request->end) {
                        $start = $request->start;
                        $end = $request->end;

                        if($request->period == 2) {
                            $start = $start . '-01';
                            $end = Carbon::createFromFormat('Y-m', $end)->endOfMonth()->format('Y-m-d');
                        } else if($request->period == 3) {
                            $start = $start . '-01-01';
                            $end = $end . '-12-31';
                        }

                        $q->whereBetween('created_at', [date($start), date($end)]);

                    } else {
                        $q->whereYear('created_at', date('Y'));
                        $q->whereMonth('created_at', date('m'));
                    }
                })
                ->where(function($q) use($type, $role) {
                    if($type == 'tuk') {
                        if($role == 'examiner-secretary') {
                            $team = $this->checkTuk();
                            if($team) {
                                if($team->authority == 'Provinsi') {
                                    $districts = $this->getDistricts($team->id_province_name);
                                    $q->where([['authority', 'Provinsi'],['auth_province', $team->id_province_name]])
                                    ->orWhere(function($que) use($districts) {
                                            $que->where('authority', 'district');
                                            $que->whereIn('auth_district', $districts);
                                        });
                                } else if($team->authority == 'Kabupaten') {
                                    $q->where([['authority', 'Kabupaten'],['auth_district', $team->id_district_name]]);
                                }
                            }
                        } else {
                            $q->whereHas('tukProject', function ($query) {
                                $query->whereHas('feasibilityTestTeamMember', function ($que) {
                                    $que->whereHas('lukMember', function ($quer) {
                                        $quer->where('email', Auth::user()->email);
                                    })->orWhereHas('expertBank', function ($quer) {
                                        $quer->where('email', Auth::user()->email);
                                    });
                                })->orWhereHas('tukSecretaryMember', function ($que) {
                                    $que->where('email', Auth::user()->email);
                                });
                            })->orWhereHas('testingMeeting', function ($query) {
                                $query->whereHas('invitations', function ($que) {
                                    $que->where(function ($queryy) {
                                        $queryy->whereHas('feasibilityTestTeamMember', function ($quer) {
                                            $quer->whereHas('lukMember', function ($qu) {
                                                $qu->where('email', Auth::user()->email);
                                            })->orWhereHas('expertBank', function ($qu) {
                                                $qu->where('email', Auth::user()->email);
                                            });
                                        })->orWhereHas('tukSecretaryMember', function ($quer) {
                                            $quer->where('email', Auth::user()->email);
                                        });
                                    })->orWhere('email', Auth::user()->email);
                                });
                            });
                        }
                    }
                })
                ->count();

        $accepted = Project::where(function($q) use($request) {
                        if($request->period && $request->start && $request->end) {
                            $start = $request->start;
                            $end = $request->end;

                            if($request->period == 2) {
                                $start = $start . '-01';
                                $end = Carbon::createFromFormat('Y-m', $end)->endOfMonth()->format('Y-m-d');
                            } else if($request->period == 3) {
                                $start = $start . '-01-01';
                                $end = $end . '-12-31';
                            }

                            $q->whereBetween('created_at', [date($start), date($end)]);
                        } else {
                            $q->whereYear('created_at', date('Y'));
                            $q->whereMonth('created_at', date('m'));
                        }
                    })
                    ->where(function($q) use($type, $role) {
                        if($type == 'tuk') {
                            if($role == 'examiner-secretary') {
                                $team = $this->checkTuk();
                                if($team) {
                                    if($team->authority == 'Provinsi') {
                                        $districts = $this->getDistricts($team->id_province_name);
                                        $q->where([['authority', 'Provinsi'],['auth_province', $team->id_province_name]])
                                        ->orWhere(function($que) use($districts) {
                                                $que->where('authority', 'district');
                                                $que->whereIn('auth_district', $districts);
                                            });
                                    } else if($team->authority == 'Kabupaten') {
                                        $q->where([['authority', 'Kabupaten'],['auth_district', $team->id_district_name]]);
                                    }
                                }
                            } else {
                                $q->whereHas('tukProject', function ($query) {
                                    $query->whereHas('feasibilityTestTeamMember', function ($que) {
                                        $que->whereHas('lukMember', function ($quer) {
                                            $quer->where('email', Auth::user()->email);
                                        })->orWhereHas('expertBank', function ($quer) {
                                            $quer->where('email', Auth::user()->email);
                                        });
                                    })->orWhereHas('tukSecretaryMember', function ($que) {
                                        $que->where('email', Auth::user()->email);
                                    });
                                })->orWhereHas('testingMeeting', function ($query) {
                                    $query->whereHas('invitations', function ($que) {
                                        $que->where(function ($queryy) {
                                            $queryy->whereHas('feasibilityTestTeamMember', function ($quer) {
                                                $quer->whereHas('lukMember', function ($qu) {
                                                    $qu->where('email', Auth::user()->email);
                                                })->orWhereHas('expertBank', function ($qu) {
                                                    $qu->where('email', Auth::user()->email);
                                                });
                                            })->orWhereHas('tukSecretaryMember', function ($quer) {
                                                $quer->where('email', Auth::user()->email);
                                            });
                                        })->orWhere('email', Auth::user()->email);
                                    });
                                });
                            }
                        }
                    })
                    ->whereHas('feasibilityTest')
                    ->count();
        
        $on_progress = Project::where(function($q) use($request) {
                            if($request->period && $request->start && $request->end) {
                                $start = $request->start;
                                $end = $request->end;

                                if($request->period == 2) {
                                    $start = $start . '-01';
                                    $end = Carbon::createFromFormat('Y-m', $end)->endOfMonth()->format('Y-m-d');
                                } else if($request->period == 3) {
                                    $start = $start . '-01-01';
                                    $end = $end . '-12-31';
                                }

                                $q->whereBetween('created_at', [date($start), date($end)]);
                            } else {
                                $q->whereYear('created_at', date('Y'));
                                $q->whereMonth('created_at', date('m'));
                            }
                        })
                        ->where(function($q) use($type, $role) {
                            if($type == 'tuk') {
                                if($role == 'examiner-secretary') {
                                    $team = $this->checkTuk();
                                    if($team) {
                                        if($team->authority == 'Provinsi') {
                                            $districts = $this->getDistricts($team->id_province_name);
                                            $q->where([['authority', 'Provinsi'],['auth_province', $team->id_province_name]])
                                            ->orWhere(function($que) use($districts) {
                                                    $que->where('authority', 'district');
                                                    $que->whereIn('auth_district', $districts);
                                                });
                                        } else if($team->authority == 'Kabupaten') {
                                            $q->where([['authority', 'Kabupaten'],['auth_district', $team->id_district_name]]);
                                        }
                                    }
                                } else {
                                    $q->whereHas('tukProject', function ($query) {
                                        $query->whereHas('feasibilityTestTeamMember', function ($que) {
                                            $que->whereHas('lukMember', function ($quer) {
                                                $quer->where('email', Auth::user()->email);
                                            })->orWhereHas('expertBank', function ($quer) {
                                                $quer->where('email', Auth::user()->email);
                                            });
                                        })->orWhereHas('tukSecretaryMember', function ($que) {
                                            $que->where('email', Auth::user()->email);
                                        });
                                    })->orWhereHas('testingMeeting', function ($query) {
                                        $query->whereHas('invitations', function ($que) {
                                            $que->where(function ($queryy) {
                                                $queryy->whereHas('feasibilityTestTeamMember', function ($quer) {
                                                    $quer->whereHas('lukMember', function ($qu) {
                                                        $qu->where('email', Auth::user()->email);
                                                    })->orWhereHas('expertBank', function ($qu) {
                                                        $qu->where('email', Auth::user()->email);
                                                    });
                                                })->orWhereHas('tukSecretaryMember', function ($quer) {
                                                    $quer->where('email', Auth::user()->email);
                                                });
                                            })->orWhere('email', Auth::user()->email);
                                        });
                                    });
                                }
                            }
                        })
                        ->whereDoesntHave('feasibilityTest')
                        ->count();

        return [
            'total' => $total,
            'accepted' => $accepted,
            'on_progress' => $on_progress,
            'rejected' => $rejected
        ];
    }

    public function permitAuthority(Request $request)
    {
        $type = Auth::user()->roles->first()->name == 'admin' ? '' : 'tuk';
        $role = Auth::user()->roles->first()->name;

        $pusat = [
            'total' => 0,
            'show' => false
        ];

        $provinsi = [
            'total' => 0,
            'show' => false
        ];

        $kabupaten = [
            'total' => 0,
            'show' => false
        ];

        $user = null;
        $team = null;
        
        if($role !== 'admin') {
            $team = $this->checkTuk();
        }

        if($role === 'examiner-secretary') {
            if($team) {
                if($team->authority == 'Pusat') {
                    $pusat = [
                        'total' => $this->getPermitAuthority($type, 'Pusat', $team, $request->period, $request->start, $request->end),
                        'show' => true
                    ];
                }

                if($team->authority == 'Pusat' || $team->authority == 'Provinsi') {
                    $provinsi = [
                        'total' => $this->getPermitAuthority($type, 'Provinsi', $team, $request->period, $request->start, $request->end),
                        'show' => true
                    ];
                }

                $kabupaten = [
                    'total' => $this->getPermitAuthority($type, 'Kabupaten/Kota', $team, $request->period, $request->start, $request->end),
                    'show' => true
                ];
            }
        } else {
            $pusat = [
                'total' => $this->getPermitAuthority('admin', 'Pusat', null, $request->period, $request->start, $request->end),
                'show' => true
            ];
    
            $provinsi = [
                'total' => $this->getPermitAuthority('admin', 'Provinsi', null, $request->period, $request->start, $request->end),
                'show' => true
            ];
    
            $kabupaten = [
                'total' => $this->getPermitAuthority('admin', 'Kabupaten/Kota', null, $request->period, $request->start, $request->end),
                'show' => true
            ];    
        }

        return [
            'pusat' => $pusat,
            'provinsi' => $provinsi,
            'kabupaten' => $kabupaten
        ];
    }

    public function initiator(Request $request)
    {
        $type = Auth::user()->roles->first()->name == 'admin' ? '' : 'tuk';
        $role = Auth::user()->roles->first()->name;
        $team = null;

        if($role == 'examiner-secretary') {
            $team = $this->checkTuk();
            if($team) {
                return $this->getInitiators($type, $team, $request->limit, $request->period, $request->start, $request->end);
            }
        } else {
            return $this->getInitiators('admin', null, $request->limit, $request->period, $request->start, $request->end);
        }

    }

    public function chart(Request $request)
    {
        Carbon::setLocale('id');
        $period = $request->period;
        $start = $request->start;
        $end = $request->end;
        $type = Auth::user()->roles->first()->name == 'admin' ? '' : 'tuk';
        $role = Auth::user()->roles->first()->name;
        $dates = [];
        $amdal = [];
        $ukl_upl = [];
        $sppl = [];
        $adendum = [];
        $range = null;
        $team = null;

        if($role !== 'admin') {
            $team = $this->checkTuk();
        }

        if($period == 1) {
            $range = CarbonPeriod::create(date('Y-m-d', strtotime($start)), date('Y-m-d', strtotime($end)))->toArray();
            foreach($range as $r) {
                $dates[] = $r->format('d-m-Y');
    
                $amdal[] = $this->getChart($type, $period, $team, null, null, $r->format('Y-m-d'), 'AMDAL', $role);
                $sppl[] = $this->getChart($type, $period, $team, null, null, $r->format('Y-m-d'), 'SPPL', $role);
                $ukl_upl[] = $this->getChart($type, $period, $team, null, null, $r->format('Y-m-d'), 'UKL-UPL', $role);
                $adendum[] = 0;
            }
        } else if($period == 2) {
            $range = CarbonPeriod::create($start . '-01', '1 month', $end . '-01')->toArray();
            foreach($range as $r) {
                $dates[] = Carbon::createFromFormat('Y-m', $r->format('Y-m'))->isoFormat('MMMM Y');
                $year = $r->format('Y');
                $month = $r->format('m');

                $amdal[] = $this->getChart($type, $period, $team, $year, $month, null, 'AMDAL', $role);
                $sppl[] = $this->getChart($type, $period, $team, $year, $month, null, 'SPPL', $role);
                $ukl_upl[] = $this->getChart($type, $period, $team, $year, $month, null, 'UKL-UPL', $role);
                $adendum[] = 0;
            }
        } else if($period == 3) {
            for($year = $start; $year <= $end; $year++) {
                $dates[] = $year;

                $amdal[] = $this->getChart($type, $period, $team, $year, null, null, 'AMDAL', $role);
                $sppl[] = $this->getChart($type, $period, $team, $year, null, null, 'SPPL', $role);
                $ukl_upl[] = $this->getChart($type, $period, $team, $year, null, null, 'UKL-UPL', $role);
                $adendum[] = 0;
            }
        } else {
            $period = 1;
            $start = date('Y-m') . '-01';
            $end = Carbon::now()->endOfMonth()->format('Y-m-d');
            
            $range = CarbonPeriod::create(date('Y-m-d', strtotime($start)), date('Y-m-d', strtotime($end)))->toArray();
            foreach($range as $r) {
                $dates[] = $r->format('d-m-Y');
    
                $amdal[] = $this->getChart($type, $period, $team, null, null, $r->format('Y-m-d'), 'AMDAL', $role);
                $sppl[] = $this->getChart($type, $period, $team, null, null, $r->format('Y-m-d'), 'SPPL', $role);
                $ukl_upl[] = $this->getChart($type, $period, $team, null, null, $r->format('Y-m-d'), 'UKL-UPL', $role);
                $adendum[] = 0;
            }
        }


        return [
            'dates' => $dates,
            'amdal' => $amdal,
            'sppl' => $sppl,
            'ukl_upl' => $ukl_upl,
            'adendum' => $adendum
        ];
        
    }

    private function getPermitAuthority($type, $authority, $team, $period, $start, $end)
    {
        $role = Auth::user()->roles->first()->name;
        return Project::where(function($q) use($period, $start, $end) {
                                if($period && $start && $end) {
                                    if($period == 2) {
                                        $start = $start . '-01';
                                        $end = Carbon::createFromFormat('Y-d', $end)->endOfMonth()->format('Y-m-d');
                                    } else if($period == 3) {
                                        $start = $start . '-01-01';
                                        $end = $end . '-12-31';
                                    }

                                    $q->whereBetween('created_at', [date($start), date($end)]);
                                } else {
                                    $q->whereYear('created_at', date('Y'));
                                    $q->whereMonth('created_at', date('m'));
                                }
                            })
                            ->where(function($q) use($type, $authority, $team, $role) {
                                if($authority == 'Pusat') {
                                    $q->where('authority', 'Pusat')->orWhere('authority', null);
                                } else if($authority == 'Provinsi') {
                                    if($type == 'tuk') {
                                        if($team) {
                                            if($team->authority == 'Pusat') {
                                                $q->where('authority', 'Provinsi');
                                            } else if($team->authority == 'Provinsi') {
                                                $q->where([['authority', 'Provinsi'],['auth_province', $team->id_province_name]]);
                                            }
                                        }
                                    } else {
                                        $q->where('authority', 'Provinsi');
                                    }
                                } else if($authority == 'Kabupaten/Kota') {
                                    if($type == 'tuk') {
                                        if($team) {
                                            if($team->authority == 'Pusat') {
                                                $q->where('authority', 'Kabupaten');
                                            } else if($team->authority == 'Provinsi') {
                                                $districts = $this->getDistricts($team->id_province_name);
                                                $q->where('authority', 'Kabupaten');
                                                $q->whereIn('auth_district', $districts);
                                            }  else if($team->authority == 'Kabupaten/Kota') {
                                                $q->where([['authority', 'Kabupaten'],['auth_district', $team->id_district_name]]);
                                            }
                                        }
                                    } else {
                                        $q->where('authority', 'Kabupaten');
                                    }
                                }

                                if(!($role == 'admin' || $role == 'examiner-secretary')) {
                                    $q->whereHas('tukProject', function ($query) {
                                        $query->whereHas('feasibilityTestTeamMember', function ($que) {
                                            $que->whereHas('lukMember', function ($quer) {
                                                $quer->where('email', Auth::user()->email);
                                            })->orWhereHas('expertBank', function ($quer) {
                                                $quer->where('email', Auth::user()->email);
                                            });
                                        })->orWhereHas('tukSecretaryMember', function ($que) {
                                            $que->where('email', Auth::user()->email);
                                        });
                                    })->orWhereHas('testingMeeting', function ($query) {
                                        $query->whereHas('invitations', function ($que) {
                                            $que->where(function ($queryy) {
                                                $queryy->whereHas('feasibilityTestTeamMember', function ($quer) {
                                                    $quer->whereHas('lukMember', function ($qu) {
                                                        $qu->where('email', Auth::user()->email);
                                                    })->orWhereHas('expertBank', function ($qu) {
                                                        $qu->where('email', Auth::user()->email);
                                                    });
                                                })->orWhereHas('tukSecretaryMember', function ($quer) {
                                                    $quer->where('email', Auth::user()->email);
                                                });
                                            })->orWhere('email', Auth::user()->email);
                                        });
                                    });
                                }
                            })
                            ->count();
    }

    private function getInitiators($type, $team, $limit, $period, $start, $end)
    {
        return Project::select('id', 'id_applicant', 'created_at', 'authority', 'auth_province', 'auth_district')
        ->where(function($q) use($period, $start, $end) {
            if($period && $start && $end) {
                if($period == 2) {
                    $start = $start . '-01';
                    $end = Carbon::createFromFormat('Y-d', $end)->endOfMonth()->format('Y-m-d');
                } else if($period == 3) {
                    $start = $start . '-01-01';
                    $end = $end . '-12-31';
                }

                $q->whereBetween('created_at', [date($start), date($end)]);
            } else {
                $q->whereYear('created_at', date('Y'));
                $q->whereMonth('created_at', date('m'));
            }
        })
        ->with(['initiator' => function($q) {
            $q->select('id', 'name');
        }, 'feasibilityTest' => function($q) {
            $q->select('id', 'id_project');
        }])
        ->where(function($q) use($type, $team) {
            if($type == 'tuk') {
                if($team->authority == 'Provinsi') {
                    $districts = $this->getDistricts($team->id_province_name);
                    $q->where([['authority', 'Provinsi'],['auth_province', $team->id_province_name]])
                    ->orWhere(function($que) use($districts) {
                          $que->where('authority', 'Kabupaten');
                          $que->whereIn('auth_district', $districts);
                      });
                } else if($team->authority == 'Kabupaten/Kota') {
                    $q->where([['authority', 'Kabupaten'],['auth_district', $team->id_district_name]]);
                }
            }

            $role = Auth::user()->roles->first()->name;
            if(!($role == 'admin' || $role == 'examiner-secretary')) {
                $q->whereHas('tukProject', function ($query) {
                    $query->whereHas('feasibilityTestTeamMember', function ($que) {
                        $que->whereHas('lukMember', function ($quer) {
                            $quer->where('email', Auth::user()->email);
                        })->orWhereHas('expertBank', function ($quer) {
                            $quer->where('email', Auth::user()->email);
                        });
                    })->orWhereHas('tukSecretaryMember', function ($que) {
                        $que->where('email', Auth::user()->email);
                    });
                })->orWhereHas('testingMeeting', function ($query) {
                    $query->whereHas('invitations', function ($que) {
                        $que->where(function ($queryy) {
                            $queryy->whereHas('feasibilityTestTeamMember', function ($quer) {
                                $quer->whereHas('lukMember', function ($qu) {
                                    $qu->where('email', Auth::user()->email);
                                })->orWhereHas('expertBank', function ($qu) {
                                    $qu->where('email', Auth::user()->email);
                                });
                            })->orWhereHas('tukSecretaryMember', function ($quer) {
                                $quer->where('email', Auth::user()->email);
                            });
                        })->orWhere('email', Auth::user()->email);
                    });
                });
            }
        })
        ->orderBy('created_at', 'desc')
        ->paginate($limit);
    }

    private function getChart($type, $period, $team, $year, $month, $date, $required_doc, $role)
    {
        return Project::where('required_doc', $required_doc)
                      ->where(function($q) use($type, $team, $role) {
                          if($type == 'tuk') {
                              if($role == 'examiner-secretary') {
                                  if($team->authority == 'Provinsi') {
                                    $districts = $this->getDistricts($team->id_province_name);
                                    $q->where([['authority', 'Provinsi'],['auth_province', $team->id_province_name]])
                                    ->orWhere(function($que) use($districts) {
                                          $que->where('authority', 'Kabupaten');
                                          $que->whereIn('auth_district', $districts);
                                      });
                                  } else if($team->authority == 'Kabupaten/Kota') {
                                      $q->where([['authority', 'Kabupaten'],['auth_district', $team->id_district_name]]);
                                  }
                              } else {
                                $q->whereHas('tukProject', function ($query) {
                                    $query->whereHas('feasibilityTestTeamMember', function ($que) {
                                        $que->whereHas('lukMember', function ($quer) {
                                            $quer->where('email', Auth::user()->email);
                                        })->orWhereHas('expertBank', function ($quer) {
                                            $quer->where('email', Auth::user()->email);
                                        });
                                    })->orWhereHas('tukSecretaryMember', function ($que) {
                                        $que->where('email', Auth::user()->email);
                                    });
                                })->orWhereHas('testingMeeting', function ($query) {
                                    $query->whereHas('invitations', function ($que) {
                                        $que->where(function ($queryy) {
                                            $queryy->whereHas('feasibilityTestTeamMember', function ($quer) {
                                                $quer->whereHas('lukMember', function ($qu) {
                                                    $qu->where('email', Auth::user()->email);
                                                })->orWhereHas('expertBank', function ($qu) {
                                                    $qu->where('email', Auth::user()->email);
                                                });
                                            })->orWhereHas('tukSecretaryMember', function ($quer) {
                                                $quer->where('email', Auth::user()->email);
                                            });
                                        })->orWhere('email', Auth::user()->email);
                                    });
                                });
                              }
                          }
                      })
                      ->where(function($q) use($period, $year, $month, $date) {
                        if($period == 1) {
                            $q->whereDate('created_at', $date);
                        } else if($period == 2) {
                            $q->whereYear('created_at', $year);
                            $q->whereMonth('created_at', $month);
                        } else if($period == 3) {
                            $q->whereYear('created_at', (String) $year);
                        } 
                      })->count();
    }

    private function checkTuk()
    {
        return FeasibilityTestTeam::whereHas('member', function($q) {
            $q->whereHas('lukMember', function($query) {
                $query->where('email', Auth::user()->email);
            })->orWhereHas('expertBank', function($query) {
                $query->where('email', Auth::user()->email);
            });
            $q->where('position', 'Kepala Sekretariat');
        })->first();
    }

    private function getDistricts($id_province)
    {
        $districts = [];
        $districts_data = District::select('id','id_prov')->where('id_prov', $id_province)->get();
        foreach($districts_data as $d) {
            $districts[] = $d->id;
        }

        return $districts;
    }
}
