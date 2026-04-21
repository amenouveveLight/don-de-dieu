

<h2 style="text-align:center; font-size:18px;">
    Statistiques du {{ \Carbon\Carbon::parse($year)->format('Y') }}
</h2>


<table border="1" cellpadding="6" cellspacing="0" width="100%" style="font-size:12px; border-collapse: collapse;">
  <thead style="background:#eee;">
    <tr>
      <th>Type d'engin</th>
      <th>Entrées</th>
      <th>Sorties</th>
      <th>Tarif</th>
      <th>Revenus</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($yearlyTableData as $stat)
      <tr>
        <td>{{ $stat['label'] }}</td>
        <td>{{ $stat['entrees'] }}</td>
        <td>{{ $stat['sorties'] }}</td>
        <td>{{ number_format($stat['tarif'], 0, ',', ' ') }} FCFA</td>
        <td>{{ number_format($stat['revenu'], 0, ',', ' ') }} FCFA</td>
      </tr>
    @endforeach
    <tr style="font-weight:bold; background:#f9f9f9;">
      <td>Total</td>
      <td>{{ $yearlyTotalEntrees }}</td>
      <td>{{ $yearlyTotalSorties }}</td>
      <td>–</td>
      <td>{{ number_format($yearlyRevenusTotaux, 0, ',', ' ') }} FCFA</td>
    </tr>
  </tbody>
</table>
