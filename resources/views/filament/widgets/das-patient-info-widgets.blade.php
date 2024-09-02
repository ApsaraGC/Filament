<!-- resources/views/filament/widgets/das-patient-info-widgets.blade.php -->

<x-filament-widgets::widget>
    <x-filament::section>
        <div class="widget-content" style="background-image: url('images/hospital.jpg'); background-size: cover; background-position: center; padding: 20px; color: #fff;">
            <h2>Welocome </h2>

            <!-- Additional content can go here -->
        </div>
        <!-- Example of displaying patient profile information -->
{{-- <div class="patient-profile">
    <h3>Patient Profile</h3>
    <p>Name: {{ $user->name }}</p>
    <p>Age: {{ $user->age }}</p>
    <p>Address: {{ $user->address }}</p>
</div> --}}
<style>
    /* resources/css/widget.css */
.widget-content {
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    color: #ffffff;
}



.patient-profile {
    margin-top: 20px;
}

</style>


    </x-filament::section>
</x-filament-widgets::widget>
