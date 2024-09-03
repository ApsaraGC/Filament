<!-- resources/views/filament/widgets/das-patient-info-widgets.blade.php -->

<x-filament-widgets::widget>
    <x-filament::section>
        <div class="widget-content" style="background-image: url('images/hospital.jpg'); background-size: cover; background-position: center; padding: 20px; color: #fff;">
            <h2>𝔸𝕝𝕨𝕒𝕪𝕤 𝔹𝕖 ℍ𝕖𝕒𝕝𝕥𝕙𝕪 𝕒𝕟𝕕 𝕊𝕒𝕗𝕖 . </h2>

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
    height: 400px;
}

h2{
    margin-top: 10px;
    font-size: 80px;
    color:rgb(210, 67, 210);
}

.patient-profile {
    margin-top: 20px;
}

</style>


    </x-filament::section>
</x-filament-widgets::widget>
