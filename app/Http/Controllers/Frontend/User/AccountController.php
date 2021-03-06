<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\ApiController;
use App\Models\Api\Discipline;
use App\Models\Api\StudentDisciplines;
use App\Repositories\Frontend\Api\DisciplineRepository;
use App\Repositories\Frontend\Api\StudentDisciplinesRepository;

/**
 * Class AccountController.
 */
class AccountController extends Controller
{

    /**
     * @var StudentDisciplinesRepository
     */
    protected $studentDisciplinesRepository;

    /**
     * @var DisciplineRepository
     */
    protected $disciplineRepository;

    public function __construct(StudentDisciplinesRepository $studentDisciplinesRepository, DisciplineRepository $disciplineRepository)
    {
        $this->studentDisciplinesRepository = $studentDisciplinesRepository;
        $this->disciplineRepository = $disciplineRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = $this->getUserInfo();
        $disciplines = $this->disciplineRepository->get();

        $disciplines_ids = [];
        foreach($user->disciplines as $discipline)
            $disciplines_ids[] = $discipline->id;

        return view('frontend.user.account')->with(['disciplines' => $disciplines, 'selectedDisciplines' => $disciplines_ids]);
    }
}
