<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Models\ZoneType;
use App\Models\RowType;
use App\Models\SeatType;
use App\Services\EventService;
use App\Services\SeatCatalogueService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SeatCatalogueController extends Controller
{

    /*
    * Inject the SeatCatalogue service into the controller
    */
    protected $seat_catalogue_service;
    protected $event_service;

    public function __construct(SeatCatalogueService $seat_catalogue_service, EventService $event_service)
    {
        $this->seat_catalogue_service = $seat_catalogue_service;
        $this->event_service = $event_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $user = Auth::user()->load('globalImages', 'userRoles');
            $flash = $user->is_new ? 'is_new_user' : null;
            $tickets = $user->EventSeatCatalogues()
                ->with('event', 'seatCatalogue', 'seatCatalogueStatus', 'seasonTicket')
                ->whereHas('seatCatalogueStatus', function ($query) {
                    $query->where('name', 'vendido');
                })
                ->get();
            $events = $this->event_service->getAll();
            $eventsWithTickets = [];

            foreach ($events as $event) {
                $eventsWithTickets[$event->id] = [
                    'event' => $event,
                    'tickets' => $tickets->filter(function($ticket) use ($event) {
                        return $ticket->event_id == $event->id;
                    })->values()
                ];
            }

            return Inertia::render('App/Member/Dashboard', [
                'user' => $user,
                'flash' => $flash,
                'events_with_tickets' => $eventsWithTickets,
            ]);

        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar el catálogo de asientos');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function saveAllSeatsForStadium()
    {

        // Revisar por que se ejecuta dos veces esta función

        try {

            $generate_seats = function ($stadium_id, $description, $is_active, $zone_type_id, $seats_per_row) {

                $data_row = [];
                $data_seats = [];
                $seat_types = SeatType::all();
                $seat_types_default = [
                    [
                        "name" => "courtside",
                        "code" => [
                            "AA",
                            "BA"
                        ],
                    ],
                    [
                        "name" => "dorado",
                        "code" => [
                            "AB", "AC",
                            "BB", "BC","BD", "BE",
                            "HA", "HB", "HC"
                        ],
                    ],
                    [
                        "name" => "purpura",
                        "code" => [
                            "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK",
                            "BF", "BG", "BH", "BI", "BJ", "BK",
                            "CA", "CB", "CC", "CD", "CE", "CF",
                            "EA", "EB", "EC",
                            "FA", "FB", "FC",
                            "HD", "HE", "HF", "HG", "HH", "HI", "HJ",
                        ],
                    ],
                    [
                        "name" => "fan",
                        "code" => [
                            "ED", "EE", "EF", "EG", "EH", "EI", "EJ",
                            "FD", "FE", "FF", "FG", "FH", "FI", "FJ",
                            "EK"
                        ],
                    ],
                ];

                foreach ($seats_per_row as $index => $number_seats) {

                    $row = $index + 1;

                    $data_row[] = [
                        "row_type_id" => $row,
                        "code" => ZoneType::find($zone_type_id)->code.RowType::find($row)->code,
                        "number_seats" => $number_seats
                    ];
                }

                foreach ($data_row as $index => $row) {

                    for ($i=0; $i < $row["number_seats"]; $i++) {

                        $number_seat = $i + 1;

                        $seat_type_id = null;

                        foreach ($seat_types_default as $index => $seat_type) {

                            if (in_array($row["code"], $seat_type["code"]))
                            {
                                $seat_type_id = $seat_types->where("name", $seat_type["name"])->first()->id;
                            }
                        }

                        $data_seats[] = [
                            "stadium_id" => $stadium_id,
                            "zone_type_id" => $zone_type_id,
                            "seat_type_id" => $seat_type_id ,
                            "row_type_id" => $row["row_type_id"],
                            'zone' => $row["code"][0],
                            'row' => $row["code"][1],
                            'seat' => $number_seat,
                            "code" => $row["code"].$number_seat,
                            "description" => $description,
                            "is_active" => $is_active,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
                return $data_seats;
            };

            $zones_stadium = [
                [
                   "stadium" => 1,
                   "description_seat" => "Asiento",
                   "seat_is_active" => true,
                   "zone_type_id" => 1,
                   "seats_per_row" => [53, 49, 49, 49, 49, 49, 49, 49, 49, 49, 49]
                ],
                [
                   "stadium" => 1,
                   "description_seat" => "Asiento",
                   "seat_is_active" => true,
                   "zone_type_id" => 2,
                   "seats_per_row" => [49, 49, 49, 49, 49, 49, 49, 49, 49, 49, 49]
                ],
                [
                   "stadium" => 1,
                   "description_seat" => "Asiento",
                   "seat_is_active" => true,
                   "zone_type_id" => 3,
                   "seats_per_row" => [34, 36, 36, 36, 38, 38]
                ],
                [
                   "stadium" => 1,
                   "description_seat" => "Asiento",
                   "seat_is_active" => true,
                   "zone_type_id" => 4,
                   "seats_per_row" => [48, 48, 48, 48, 48, 48, 48, 48, 48, 48, 54]
                ],
                [
                   "stadium" => 1,
                   "description_seat" => "Asiento",
                   "seat_is_active" => true,
                   "zone_type_id" => 5,
                   "seats_per_row" => [38, 38, 48, 48, 48, 48, 48, 48, 48, 51]
                ],
                [
                   "stadium" => 1,
                   "description_seat" => "Asiento",
                   "seat_is_active" => true,
                   "zone_type_id" => 6,
                   "seats_per_row" => [41, 41, 41, 41, 41, 41, 41, 41, 41, 41]
                ],
            ];

            $data_seats_for_stadium = [];

            foreach ($zones_stadium as $index => $zone) {

                $data_seats_for_stadium = array_merge($data_seats_for_stadium, $generate_seats(
                    $zone["stadium"],
                    $zone["description_seat"],
                    $zone["seat_is_active"],
                    $zone["zone_type_id"],
                    $zone["seats_per_row"]
                ));
            }

            $out = new \Symfony\Component\Console\Output\ConsoleOutput();
            $out->writeln("done");


            $save_all_seats_for_stadium = $this->seat_catalogue_service->saveAllSeatsForStadium($data_seats_for_stadium);

            return WebResponseHelper::sendResponse($save_all_seats_for_stadium, "Asientos guardados con éxito", null, false);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al guardar los asientos');
        }
    }
}
