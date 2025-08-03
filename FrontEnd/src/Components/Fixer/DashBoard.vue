<template>
  <div>
    <div class="container w-full">
      <!-- The card top -->
      <div class="mb-3 d-flex w-full">
        <div class="row w-full h-auto w-100 rounded-2 p-0 d-flex p-3">
          <div class="card border-0 w-100">
            <div class="card-body row d-flex g-3 justify-content-between">
              <div
                class="cards d-flex flex-column shadow-lg gap-3 justify-content-center align-items-center"
              >
                <h5 class="card-title">Repaired</h5>
                <h5
                  class="card-title rounded-3 d-flex border-1 p-2 w-auto align-items-center justify-content-center shadow"
                >
                  1
                </h5>
              </div>
              <div
                class="cards d-flex flex-column shadow-lg gap-3 justify-content-center align-items-center"
              >
                <h5 class="card-title">Currently</h5>
                <h5
                  class="card-title rounded-3 d-flex border-1 p-2 w-auto align-items-center justify-content-center shadow"
                >
                  1
                </h5>
              </div>
              <div
                class="cards d-flex flex-column shadow-lg gap-3 justify-content-center align-items-center"
              >
                <h5 class="card-title">Booked</h5>
                <h5
                  class="card-title rounded-3 d-flex border-1 p-2 w-auto align-items-center justify-content-center shadow"
                >
                  0
                </h5>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- The end card top -->

      <!-- Calendar -->
      <div class="col-sm-4 w-100 h-100">
        <div class="card w-100 ml-2 border-0 p-4">
          <div id="app">
            <ejs-schedule height="550px" currentView="Month" :eventSettings="appointmentData">
            </ejs-schedule>
            <!-- <ejs-schedule height="550px" currentView="Month" :selectedDate="schedulerSelectedDate" 
  :eventSettings="appointmentData">
  </ejs-schedule> -->
          </div>
        </div>
      </div>
      <!-- The end Calendar -->
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { ScheduleComponent, Day, Week, WorkWeek, Month, Agenda } from '@syncfusion/ej2-vue-schedule'
import { DataManager, WebApiAdaptor } from '@syncfusion/ej2-data'
var remoteData = new DataManager({
  url: 'https://ej2services.syncfusion.com/production/web-services/api/Schedule',
  adaptor: new WebApiAdaptor(),
  crossDomain: true
})

export default defineComponent({
  name: 'HistoryView',
  components: {
    'ejs-schedule': ScheduleComponent
  },
  provide : {
    schedule : [Day, Week, WorkWeek, Month, Agenda]
  },
  data() {
    return {
    
      appointmentData : {
        dataSource : [
            {
  Subject: 'Project Meeting',
  StartTime: new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), 9, 0),
  EndTime: new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), 10, 0)
},
{
  Subject: 'Review Meeting',
  StartTime: new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), 10, 0),
  EndTime: new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), 11, 30),
  IsReadonly: true
},
{
  Subject: 'Status Meeting',
  StartTime: new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), 11, 30),
  EndTime: new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), 12, 30)
}

        ]
      }
    }
  },
  methods: {
    handleEventClick(info) {
      console.log('Clicked event:', info.event)
      console.log('Event title:', info.event.title)
      console.log('Event date:', info.event.start.toISOString()) // Example: Get ISO date string

    }
  }
})
</script>

<style>
@import '../../../node_modules/@syncfusion/ej2-base/styles/material.css';
@import '../../../node_modules/@syncfusion/ej2-buttons/styles/material.css';
@import '../../../node_modules/@syncfusion/ej2-calendars/styles/material.css';
@import '../../../node_modules/@syncfusion/ej2-dropdowns/styles/material.css';
@import '../../../node_modules/@syncfusion/ej2-inputs/styles/material.css';
@import '../../../node_modules/@syncfusion/ej2-navigations/styles/material.css';
@import '../../../node_modules/@syncfusion/ej2-navigations/styles/material.css';
@import '../../../node_modules/@syncfusion/ej2-popups/styles/material.css';
@import '../../../node_modules/@syncfusion/ej2-vue-schedule/styles/material.css';

.cards {
  width: 250px;
  height: 120px;
  border-radius: 5px 5px 5px 50px;
  background: #fff;
  border-left: 20px solid orange;
  transition: 0.3s;
  color: #434343;
}
.cards:hover {
  border-left: 5px solid orange;
  background-color: rgb(241, 241, 241);
  color: orange;
}
</style>
