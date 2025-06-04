<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\ECHISOutboundService;
use App\Models\Referral;

class ECHISWebhookController extends Controller
{
    protected $outboundService;

    public function __construct(ECHISOutboundService $outboundService)
    {
        $this->outboundService = $outboundService;
    }

    /**
     * Handle incoming webhook from eCHIS
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request)
    {
        try {
            $data = $request->all();
            Log::info('Received webhook from eCHIS', ['data' => $data]);

            // Validate the webhook data
            $this->validateWebhookData($data);

            // Process the referral update
            $referral = $this->processReferralUpdate($data);

            // Send acknowledgment back to eCHIS
            $response = $this->outboundService->processOutbound([
                'referral_id' => $referral->id,
                'status' => 'acknowledged',
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Webhook processed successfully',
                'data' => $response
            ]);

        } catch (\Exception $e) {
            Log::error('Error processing eCHIS webhook', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error processing webhook: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate the webhook data
     *
     * @param array $data
     * @throws \Exception
     */
    protected function validateWebhookData($data)
    {
        $requiredFields = ['referral_id', 'status', 'facility_id'];
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new \Exception("Missing required field: {$field}");
            }
        }
    }

    /**
     * Process the referral update
     *
     * @param array $data
     * @return Referral
     */
    protected function processReferralUpdate($data)
    {
        $referral = Referral::where('referral_id', $data['referral_id'])->first();

        if (!$referral) {
            throw new \Exception("Referral not found: {$data['referral_id']}");
        }

        $referral->update([
            'status' => $data['status'],
            'facility_id' => $data['facility_id'],
            'notes' => $data['notes'] ?? $referral->notes,
            'updated_at' => now()
        ]);

        return $referral;
    }
} 